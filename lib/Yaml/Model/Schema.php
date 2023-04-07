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

namespace MwbExporter\Formatter\Propel1\Yaml\Model;

use MwbExporter\Configuration\Comment as CommentConfiguration;
use MwbExporter\Configuration\Indentation as IndentationConfiguration;
use MwbExporter\Formatter\Propel1\Configuration\ModelNamespace as ModelNamespaceConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\Connection as ConnectionConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\Package as PackageConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Configuration\ValueIndentation as ValueIndentationConfiguration;
use MwbExporter\Formatter\Propel1\Yaml\Formatter;
use MwbExporter\Helper\Comment;
use MwbExporter\Model\Schema as BaseSchema;
use MwbExporter\Object\YAML;
use MwbExporter\Writer\WriterInterface;

class Schema extends BaseSchema
{
    protected $inline_keys = ['columns', 'indexes', 'uniques', 'foreignKeys'];

    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Model\Schema::write()
     */
    public function write(WriterInterface $writer)
    {
        $data = $this->asYAML();
        /** @var \MwbExporter\Configuration\Indentation $indentation */
        $indentation = $this->getConfig(IndentationConfiguration::class);
        $indent = strlen($indentation->getIndentation(1));
        $size = $this->getInlineSize($data, 0, $indent) + 3;
        if (($maxSize = $this->getConfig(ValueIndentationConfiguration::class)->getValue()) > 0) {
            $size = min($size, $maxSize);
        }
        $yaml = new YAML($data, [
            'indent' => $indent,
            'inline' => true,
            'inline_size' => $size,
        ]);
        $writer
            ->open($this->getDocument()->translateFilename(null, $this))
            ->writeCallback(function(WriterInterface $writer, Schema $_this = null) {
                if ($_this->getConfig(CommentConfiguration::class)->getValue()) {
                    $writer
                        ->write($_this->getFormatter()->getComment(Comment::FORMAT_YAML))
                        ->write('')
                    ;
                }
            })
            ->write($yaml)
            ->close()
        ;

        return $this;
    }

    public function asYAML()
    {
        $data = [
            'connection' => $this->getConfig(ConnectionConfiguration::class)->getValue(),
            'defaultIdMethod' => 'native',
        ];
        if ($namespace = trim($this->getConfig(ModelNamespaceConfiguration::class)->getValue())) {
            $data['namespace'] = $namespace;
        }
        if ($package = trim($this->getConfig(PackageConfiguration::class)->getValue())) {
            $data['package'] = $package;
        }
        $classes = [];
        foreach ($this->getTables() as $table) {
            if ($table->isExternal()) {
                continue;
            }
            if (!count($attributes = $table->asYAML())) {
                continue;
            }
            $classes = array_merge($classes, $attributes);
        }
        if (count($classes)) {
            $data['classes'] = $classes;
        }

        return $data;
    }

    /**
     * Get the longest length for inline indentation.
     *
     * @param array $data
     * @param int $level
     * @param int $indent
     * @return int
     */
    protected function getInlineSize($data, $level = 0, $indent = 2)
    {
        $size = 0;
        $isz = $level * $indent;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (in_array($k, $this->inline_keys) && is_array($v)) {
                    $size = max([$size, $isz + $indent + $this->getMaxKeysLength($v)]);
                } else {
                    $size = max([$size, $this->getInlineSize($v, $level + 1, $indent)]);
                }
            }
        }

        return $size;
    }

    /**
     * Get the longest key length of array.
     *
     * @param array $array
     * @return int
     */
    protected function getMaxKeysLength($array)
    {
        $len = 0;
        foreach ($array as $k => $v) {
            if (is_string($k)) {
                $len = max([$len, strlen($k)]);
            }
        }

        return $len;
    }
}
