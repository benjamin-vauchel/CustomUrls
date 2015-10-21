CustomUrls Extra for MODx Revolution
=======================================


**Author:** Benjamin Vauchel <contact@omycode.fr>


This extra allows you to define custom alias or URI patterns for your resources. It supports translit and Redirector packages.
You can build your patterns from resource fields, TV, snippets and output filters and set some constraints like you'd do with custom forms.
Usefull when you want to add id or published date to your URLs.

### Examples of URL patterns

| Pattern | Comment |
----------|----------
[[+alias]]|All common MODX placeholders available|
|some-text-before-[[+alias]]|Prefix or suffix your placeholders|
|[[+id]]-[[+alias]]|Combine placeholders|
|[[+publishedon:strtotime:date=\`%Y-%m-%d\`]]/[[+id]]-[[+alias]]|Create complex URLs|
|[[+tv.mytv]]-[[+id]]|Combine template variable values|
|[[MySnippet? &id=\`[[+id]]\`]]|Must be called uncached and return a value|

Documentation: http://rtfm.modx.com/display/ADDON/CustomUrls

Bugs and Feature Requests: https://github.com/benjamin-vauchel/customurls
