# Propel 1.x YAML Schema Configuration

Auto generated at 2023-04-28T09:14:39+0700.

## Global Configuration

  * `language`

    Language detemines which language used to transform singular and plural words
    used in certains other schema like Doctrine.

    Valid values: `none`, `english`, `french`, `norwegian-bokmal`, `portuguese`, `spanish`,
    `turkish`

    Default value: `none`

  * `useTab` (alias: `useTabs`)

    Use tab as blank instead of space in generated files.

    Default value: `false`

  * `indentation`

    Number of spaces used as blank in generated files.

    Default value: `2`

  * `eolDelimiter` (alias: `eolDelimeter`)

    End of line (EOL) delimiter detemines the end of line in generated files.

    Valid values: `win`, `unix`

    Default value: `win`

  * `filename`

    The output filename format, use the following tag `%schema%`, `%table%`, `%entity%`, and
    `%extension%` to allow the filename to be replaced with contextual data.

    Default value: `%schema%.schema.%extension%`

  * `backupExistingFile`

    Perform backup existing file before writing generated file.

    Backup file will have an extension of *.bak.

    Default value: `true`

  * `headerFile`

    Include file as header in the generated files. It will be wrapped as a
    comment by choosen formatter. This configuration useful as for example
    to include notice to generated files such as license file.

    Default value: `blank`

  * `addGeneratorInfoAsComment`

    Include generated information as a comment in generated files.

    Default value: `true`

  * `namingStrategy`

    Naming strategy detemines how objects, variables, and methods name will be generated.

    Valid values: `as-is`, `camel-case`, `pascal-case`

    Default value: `pascal-case`

  * `identifierStrategy`

    Determines how identifier like table name will be treated for generated
    entity/model name. Supported identifier strategies are `fix-underscore`
    which will fix for double underscore to single underscore, and `none` which
    will do nothing.

    Valid values: `none`, `fix-underscore`

    Default value: `none`

  * `cleanUserDatatypePrefix` (alias: `asIsUserDatatypePrefix`)

    Clean user datatype matched the prefix specified.

    Default value: `blank`

  * `enhanceManyToManyDetection`

    Allows generate additional model for many to many relations.

    Default value: `true`

  * `sortTableAndView` (alias: `sortTablesAndViews`)

    Perform table name and view name sorting before generating files.

    Default value: `true`

  * `skipManyToManyTables`

    Skip many to many table generation.

    Default value: `true`

  * `skipPluralNameChecking`

    Skip checking the plural name of model and leave as is, useful for non English
    table names.

    Default value: `false`

  * `exportOnlyInCategory` (alias: `exportOnlyTableCategorized`)

    Some models may have category defined in comment, process only if it is matched.

    Default value: `blank`

  * `logToConsole`

    Activate logging to console.

    Default value: `false`

  * `logFile`

    Activate logging to filename.

    Default value: `blank`

  * `useLoggedStorage`

    Useful to use the generated files content for further processing.

    Default value: `false`

## Propel 1.0 Global Configuration

  * `namespace`

    The model namespace.

    Default value: `blank`

## Propel 1.0 Yaml Configuration

  * `package`

    Package name.

    Default value: `blank`

  * `connection`

    Propel database connection name.

    Default value: `default`

  * `generateSimpleColumn`

    Allows to generate simplest column data.

    Default value: `false`

  * `validateFkPhpName`

    Check generated foreign key PHP name for duplicates.

    Default value: `true`

  * `fkPhpNameFromModel`

    Generate foreign key PHP name using model name.

    Default value: `true`

  * `valueIndentationMax`

    Configure maximum value indentation size, set to 0 for no limit.

    Default value: `0`

