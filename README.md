# README

This is an exporter to convert [MySQL Workbench](http://www.mysql.com/products/workbench/) Models (\*.mwb) to Propel 1 YAML and XML Schema.

## Prerequisites

  * PHP 5.4+
  * Composer to install the dependencies

## Installation

```
php composer.phar require --dev mysql-workbench-schema-exporter/propel-exporter
```

This will install the exporter and also require [mysql-workbench-schema-exporter](https://github.com/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter).

You then can invoke the CLI script using `vendor/bin/mysql-workbench-schema-export`.

## Formatter Setup Options

Additionally to the [common options](https://github.com/mysql-workbench-schema-exporter/mysql-workbench-schema-exporter#configuring-mysql-workbench-schema-exporter) of mysql-workbench-schema-exporter these options are supported:

### Propel 1.x XML Schema

#### Setup Options

  * `namespace`

    The table namespace.

  * `addVendor`

    Add mysql specific vendor info into the generated content.

    Default is `false`.

### Propel 1.x YAML Schema

#### Setup Options

  * `generateSimpleColumn`

    If enabled, use simple column definition. Table columns considered as simple column are
    `created_at` and `updated_at`.

    Default is `false`.

  * `package`

    Model package.

    Default is `lib.model`.

#### Model Comment Behavior

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
