<?php

/*
 * The MIT License
 *
 * Copyright (c) 2010 Johannes Mueller <circus2(at)web.de>
 * Copyright (c) 2012-2023 Toha <tohenk@yahoo.com>
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

namespace MwbExporter\Formatter\Propel1\Yaml;

use MwbExporter\Configuration\Filename as FilenameConfiguration;
use MwbExporter\Configuration\NamingStrategy as NamingStrategyConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\Connection as ConnectionConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\ForeignKeyFromModel as ForeignKeyFromModelConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\ForeignKeyValidate as ForeignKeyValidateConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\Package as PackageConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\SimpleColumn as SimpleColumnConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\ValueIndentation as ValueIndentationConfiguration;
use MwbExporter\Formatter\Propel1\Formatter as BaseFormatter;
use MwbExporter\Model\Base;

class Formatter extends BaseFormatter
{
    protected function init()
    {
        parent::init();
        $this->getConfigurations()
            ->add(new PackageConfiguration())
            ->add(new ConnectionConfiguration())
            ->add(new SimpleColumnConfiguration())
            ->add(new ForeignKeyValidateConfiguration())
            ->add(new ForeignKeyFromModelConfiguration())
            ->add(new ValueIndentationConfiguration())
            ->merge([
                FilenameConfiguration::class => '%schema%.schema.%extension%',
                NamingStrategyConfiguration::class => NamingStrategyConfiguration::PASCAL_CASE,
            ], true)
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Formatter\Formatter::createDatatypeConverter()
     */
    protected function createDatatypeConverter()
    {
        return new DatatypeConverter();
    }

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Formatter\Formatter::createSchema()
     */
    public function createSchema(Base $parent, $node)
    {
        return new Model\Schema($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Formatter\Formatter::createTable()
     */
    public function createTable(Base $parent, $node)
    {
        return new Model\Table($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Formatter\FormatterInterface::createColumn()
     */
    public function createColumn(Base $parent, $node)
    {
        return new Model\Column($parent, $node);
    }

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Formatter\FormatterInterface::createIndex()
     */
    public function createIndex(Base $parent, $node)
    {
        return new Model\Index($parent, $node);
    }

    public function getTitle()
    {
        return 'Propel 1.x YAML Schema';
    }

    public function getFileExtension()
    {
        return 'yml';
    }

    /**
     * Get configuration scope.
     *
     * @return string
     */
    public static function getScope()
    {
        return 'Propel 1.0 Yaml';
    }
}
