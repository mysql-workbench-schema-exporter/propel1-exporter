<?php
/*
 * The MIT License
 *
 * Copyright (c) 2010 Johannes Mueller <circus2(at)web.de>
 * Copyright (c) 2012-2014 Toha <tohenk@yahoo.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace MwbExporter\Formatter\Propel1\Yaml\Model;

use MwbExporter\Model\Column as BaseColumn;
use MwbExporter\Writer\WriterInterface;
use MwbExporter\Formatter\Propel1\Yaml\Formatter;

class Column extends BaseColumn
{
    /**
     * List of column names considered as simple column.
     *
     * @var array
     */
    protected $simpleColumns = ['created_at', 'updated_at'];
    
    protected function getForeignTableCount($table)
    {
        $count = 0;
        foreach ($this->getTable()->getForeignKeys() as $foreign) {
            if (count($foreign->getLocals()) > 1) {
                continue;
            }
            if ($foreign->getReferencedTable()->getModelName() == $table) {
                $count++;
            }
        }

        return $count;
    }

    public function asYAML()
    {
        if ($this->getConfig()->get(Formatter::CFG_GENERATE_SIMPLE_COLUMN) && in_array($this->getColumnName(), $this->simpleColumns)) {
            $attributes = null;
        } else {
            $attributes = [];
            $type = strtolower($this->getFormatter()->getDatatypeConverter()->getType($this));
            $attributes['type'] = $type;
            switch ($type) {
                case 'decimal':
                    $attributes['size'] = $this->parameters->get('precision');
                    if (null !== $this->parameters->get('scale')) {
                        $attributes['scale'] = $this->parameters->get('scale');
                    }
                    break;
    
                case 'enum':
                    break;
            }
            if ($this->parameters->get('length') > 0) {
                $attributes['size'] = $this->parameters->get('length');
            }
            if ($this->isNotNull()) {
                $attributes['required'] = true;
            }
            if (1 == $this->isPrimary()) {
                $attributes['primaryKey'] = true;
            }
            if ($this->isAutoIncrement()) {
                $attributes['autoIncrement'] = true;
            }
            if (($defaultValue = $this->getDefaultValue()) && !in_array($defaultValue, ['CURRENT_TIMESTAMP'])) {
                $attributes['defaultExpr'] = $defaultValue;
            }
            // simple foreign key
            foreach ($this->foreigns as $foreign) {
                if (count($foreign->getLocals()) > 1) {
                    continue;
                }
                $attributes['foreignTable'] = $foreign->getReferencedTable()->getRawTableName();
                $attributes['foreignReference'] = $foreign->getForeign()->getColumnName();
                if (($action = strtolower($foreign->parameters->get('updateRule'))) !== 'no action') {
                    $attributes['onUpdate'] = $action;
                }
                if (($action = strtolower($foreign->parameters->get('deleteRule'))) !== 'no action') {
                    $attributes['onDelete'] = $action;
                }
                // validate foreign referenced table name for name conflict with
                // table columns
                if ($this->getConfig()->get(Formatter::CFG_VALIDATE_FK_PHP_NAME)) {
                    $foreignTableName = $foreign->getReferencedTable()->getModelName();
                    $foreignCount = $this->getForeignTableCount($foreignTableName);
                    $columns = array_map('strtolower', $this->getTable()->getColumns()->getColumnNames());
                    if ($foreignCount > 1 || in_array(strtolower($foreign->getReferencedTable()->getRawTableName()), $columns)) {
                        if ($foreignCount == 1 && !$this->getConfig()->get(Formatter::CFG_FK_PHP_NAME_FROM_MODEL)) {
                            $foreignTableName .= 'FK';
                        } else {
                            $foreignTableName .= $this->getNaming($this->getTable()->formatRelatedName($this->getColumnName()));
                        }
                        $attributes['fkPhpName'] = $foreignTableName;
                    }
                }
            }
            // column description
            if ($comment = $this->getComment(false)) {
                $attributes['description'] = $comment;
            }
        }

        return [$this->getColumnName() => $attributes];
    }
}
