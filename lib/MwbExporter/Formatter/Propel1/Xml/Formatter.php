<?php

/*
 * The MIT License
 *
 * Copyright (c) 2010 Johannes Mueller <circus2(at)web.de>
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

namespace MwbExporter\Formatter\Propel1\Xml;

use MwbExporter\Formatter as BaseFormatter;
use MwbExporter\Model\Base;

class Formatter extends BaseFormatter
{
    const CFG_NAMESPACE  = 'namespace';
    const CFG_ADD_VENDOR = 'addVendor';

    protected function init()
    {
        $this->setDatatypeConverter(new DatatypeConverter());
        $this->addConfigurations(array(
            static::CFG_INDENTATION            => 4,
            static::CFG_FILENAME               => '%schema%.schema.xml',
            static::CFG_NAMESPACE              => '',
            static::CFG_ADD_VENDOR             => false,
        ));
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.Formatter::createSchema()
     */
    public function createSchema(Base $parent, $node)
    {
        return new Model\Schema($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.Formatter::createTable()
     */
    public function createTable(Base $parent, $node)
    {
        return new Model\Table($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.FormatterInterface::createColumns()
     */
    public function createColumns(Base $parent, $node)
    {
        return new Model\Columns($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.FormatterInterface::createColumn()
     */
    public function createColumn(Base $parent, $node)
    {
        return new Model\Column($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.FormatterInterface::createIndex()
     */
    public function createIndex(Base $parent, $node)
    {
        return new Model\Index($parent, $node);
    }
    
    /**
     * (non-PHPdoc)
     * @see MwbExporter.FormatterInterface::createTables()
     */
    public function createTables(Base $parent, $node)
    {
        return new Model\Tables($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see MwbExporter.FormatterInterface::createView()
     */
    public function createView(Base $parent, $node)
    {
        return new Model\View($parent, $node);
    }

    public function getTitle()
    {
        return 'Propel1 Xml Classes';
    }

    public function getFileExtension()
    {
        return 'xml';
    }
}