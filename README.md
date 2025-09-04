![Build Status](https://github.com/mysql-workbench-schema-exporter/propel1-exporter/actions/workflows/continuous-integration.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/mysql-workbench-schema-exporter/propel1-exporter/v/stable.svg)](https://packagist.org/packages/mysql-workbench-schema-exporter/propel1-exporter)
[![Total Downloads](https://poser.pugx.org/mysql-workbench-schema-exporter/propel1-exporter/downloads.svg)](https://packagist.org/packages/mysql-workbench-schema-exporter/propel1-exporter) 
[![License](https://poser.pugx.org/mysql-workbench-schema-exporter/propel1-exporter/license.svg)](https://packagist.org/packages/mysql-workbench-schema-exporter/propel1-exporter)

# README

This is an exporter to convert [MySQL Workbench](http://www.mysql.com/products/workbench/) Models (\*.mwb) to Propel 1 YAML and XML Schema.

## Prerequisites

  * PHP 7.4+
  * Composer to install the dependencies

## Installation

```
composer require --dev mysql-workbench-schema-exporter/propel-exporter
```

This will install the exporter and also require [mysql-workbench-schema-exporter](https://github.com/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter).

You then can invoke the CLI script using `vendor/bin/mysql-workbench-schema-export`.

## Configuration

  * [Propel 1.0 XML Schema](/docs/propel1-xml.md)
  * [Propel 1.0 YAML Schema](/docs/propel1-yaml.md)

## Model Comment Behavior

  * `{propel:allowPkInsert}true{/propel:allowPkInsert}` (applied to Table)

    Allow primary key value insertion if its an auto increment column.

  * `{propel:propel_behaviors}behavior{/propel:propel_behaviors}` (applied to Table)

    Propel behaviors definition, written in YAML format.

    Example usage:

        {propel:propel_behaviors}
        timestampable:
        {/propel:propel_behaviors}

  * `{propel:behaviors}behavior{/propel:behaviors}` (applied to Table)

    Custom behaviors definition, written in YAML format.

## Command Line Interface (CLI)

See documentation for [mysql-workbench-schema-exporter](https://github.com/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter#command-line-interface-cli)

## Links

  * [MySQL Workbench](http://wb.mysql.com/)
  * [Propel Project](http://propelorm.org/)
