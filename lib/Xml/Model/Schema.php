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

use MwbExporter\Configuration\Comment as CommentConfiguration;
use MwbExporter\Configuration\Header as HeaderConfiguration;
use MwbExporter\Formatter\Propel1\Configuration\ModelNamespace as ModelNamespaceConfiguration;
use MwbExporter\Model\Schema as BaseSchema;
use MwbExporter\Writer\WriterInterface;

class Schema extends BaseSchema
{
    /**
     * (non-PHPdoc)
     * @see \MwbExporter\Model\Schema::write()
     */
    public function write(WriterInterface $writer)
    {
        $writer
            ->open($this->getDocument()->translateFilename(null, $this))
            ->writeCallback(function(WriterInterface $writer, ?Schema $_this = null) {
                /** @var \MwbExporter\Configuration\Header $header */
                $header = $this->getConfig(HeaderConfiguration::class);
                if ($content = $header->getHeader()) {
                    $writer
                        ->writeComment($content)
                        ->write('')
                    ;
                }
                if ($_this->getConfig(CommentConfiguration::class)->getValue()) {
                    if ($content = $_this->getFormatter()->getComment(null)) {
                        $writer
                            ->writeComment($content)
                            ->write('')
                        ;
                    }
                }
            })
            ->write('<?xml version="1.0" encoding="UTF-8"?>')
            ->write(
                '<database name="%s" defaultIdMethod="native"%s>',
                $this->getName(),
                ($namespace = $this->getConfig(ModelNamespaceConfiguration::class)->getValue()) ? sprintf(' namespace="%s"', $namespace) : ''
            )
            ->writeCallback(function(WriterInterface $writer, ?Schema $_this = null) {
                $_this->writeSchema($writer);
            })
            ->write('</database>')
            ->close()
        ;

        return $this;
    }
}
