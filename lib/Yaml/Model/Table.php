<?php
/*
 * The MIT License
 *
 * Copyright (c) 2010 Johannes Mueller <circus2(at)web.de>
 * Copyright (c) 2012-2024 Toha <tohenk@yahoo.com>
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

use MwbExporter\Formatter\Propel1\Yaml\Configuration\Package as PackageConfiguration;
use MwbExporter\Model\Table as BaseTable;
use Symfony\Component\Yaml\Yaml;

class Table extends BaseTable
{
    public function asYAML()
    {
        $data = [
            'tableName' => $this->getRawTableName(),
        ];
        if ($namespace = trim((string) $this->parseComment('namespace'))) {
            $data['namespace'] = $namespace;
        }
        if ($package = $this->parseComment('package')) {
            $basePackage = $this->getConfig(PackageConfiguration::class)->getValue();
            $data['package'] = ($basePackage ? $basePackage.'.' : '').$package;
        }
        if ('true' == trim((string) $this->parseComment('allowPkInsert'))) {
            $data['allowPkInsert'] = true;
        }
        $columns = [];
        foreach ($this->getColumns() as $column) {
            if (!count($attributes = $column->asYAML())) {
                continue;
            }
            $columns = array_merge($columns, $attributes);
        }
        if (count($columns)) {
            $data['columns'] = $columns;
        }
        $indexes = [];
        foreach ($this->getIndices() as $index) {
            if (!$index->isIndex()) {
                continue;
            }
            if (!count($attributes = $index->asYAML())) {
                continue;
            }
            $indexes = array_merge($indexes, $attributes);
        }
        if (count($indexes)) {
            $data['indexes'] = $indexes;
        }
        $uniques = [];
        foreach ($this->getIndices() as $index) {
            if (!$index->isUnique()) {
                continue;
            }
            if (!count($attributes = $index->asYAML())) {
                continue;
            }
            $uniques = array_merge($uniques, $attributes);
        }
        if (count($uniques)) {
            $data['uniques'] = $uniques;
        }
        $foreignKeys = [];
        foreach ($this->foreignKeys as $foreign) {
            if (count($locals = $foreign->getLocals()) == 1) {
                continue;
            }
            $attributes = ['foreignTable' => $foreign->getReferencedTable()->getRawTableName()];
            if (($action = strtolower($foreign->getParameters()->get('updateRule'))) !== 'no action') {
                $attributes['onUpdate'] = $action;
            }
            if (($action = strtolower($foreign->getParameters()->get('deleteRule'))) !== 'no action') {
                $attributes['onDelete'] = $action;
            }
            $references = [];
            $foreigns = $foreign->getForeigns();
            for ($i = 0; $i < count($locals); $i++) {
                $lcol = $locals[$i];
                $fcol = $foreigns[$i];
                $references[] = ['local' => $lcol->getColumnName(), 'foreign' => $fcol->getColumnName()];
            }
            $attributes['references'] = $references;
            $foreignKeys = array_merge($foreignKeys, [$foreign->getParameters()->get('name') => $attributes]);
        }
        if (count($foreignKeys)) {
            $data['foreignKeys'] = $foreignKeys;
        }
        foreach (['propel_behaviors', 'behaviors'] as $key) {
            if ($behavior = trim((string) $this->parseComment($key))) {
                try {
                    $behavior = Yaml::parse($behavior);
                    $data[$key] = $behavior;
                } catch (\Exception $e) {
                    $this->getDocument()->addLog(sprintf('  Skip %s: %s', $key, $e->getMessage()));
                }
            }
        }

        return [$this->getModelName() => $data];
    }
}
