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

namespace MwbExporter\Formatter\Propel1\Xml\Model;

use MwbExporter\Formatter\Propel1\Xml\Configuration\Vendor as VendorConfiguration;
use MwbExporter\Model\Table as BaseTable;
use MwbExporter\Writer\WriterInterface;

class Table extends BaseTable
{
    public function writeTable(WriterInterface $writer)
    {
        if (!$this->isExternal()) {
            $writer
                ->indent()
                    ->write(
                        '<table name="%s" phpName="%s"%s>',
                        $this->getRawTableName(),
                        $this->getModelName(),
                        ($namespace = trim((string) $this->parseComment('namespace'))) ? sprintf(' namespace="%s"', $namespace) : ''
                    )
                    ->indent()
                        ->writeCallback(function(WriterInterface $writer, ?Table $_this = null) {
                            if ($_this->getConfig(VendorConfiguration::class)->getValue()) {
                                $_this->writeVendor($writer);
                            }
                            $_this->getColumns()->write($writer);
                            $_this->writeIndex($writer);
                            $_this->writeRelations($writer);
                        })
                    ->outdent()
                    ->write('</table>')
                ->outdent()
            ;

            return self::WRITE_OK;
        }

        return self::WRITE_EXTERNAL;
    }

    public function writeVendor(WriterInterface $writer)
    {
        $writer->write('<vendor type="mysql">');
        $writer->indent();
        $writer->write('<parameter name="Engine" value="%s" />', $this->parameters->get('tableEngine'));
        $writer->write('<parameter name="Charset" value="%s" />', $this->parameters->get('defaultCharacterSetName'));
        $writer->outdent();
        $writer->write('</vendor>');

        return $this;
    }

    public function writeIndex(WriterInterface $writer)
    {
        foreach ($this->getTableIndices() as $index) {
            $index->write($writer);
        }

        return $this;
    }

    public function writeRelations(WriterInterface $writer)
    {
        foreach ($this->foreignKeys as $foreign) {
            $writer
                ->write(
                    '<foreign-key name="%s" foreignTable="%s" phpName="%s" refPhpName="%s" onDelete="%s" onUpdate="%s">',
                    $foreign->parameters->get('name'),
                    $foreign->getReferencedTable()->getRawTableName(),
                    $foreign->getReferencedTable()->getModelName(),
                    $foreign->getOwningTable()->getModelName(),
                    (strtolower($foreign->parameters->get('deleteRule')) == 'no action' ? 'none' : strtolower($foreign->parameters->get('deleteRule'))),
                    (strtolower($foreign->parameters->get('updateRule')) == 'no action' ? 'none' : strtolower($foreign->parameters->get('updateRule')))
                )
            ;
            $writer->indent();
            $locals = $foreign->getLocals();
            $foreigns = $foreign->getForeigns();
            for ($i = 0; $i < count($locals); $i++) {
                $writer
                    ->write(
                        '<reference local="%s" foreign="%s" />',
                        $locals[$i]->getColumnName(),
                        $foreigns[$i]->getColumnName()
                    )
                ;
            }
            $writer->outdent();
            $writer->write('</foreign-key>');
        }

        return $this;
    }
}
