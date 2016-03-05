This plugin is for multi-language sites where most of the content is translated, but some of the content is only available in a single language - but multi-language menus cause duplication of pages, resulting in duplicate content.

For example, if you had a site with English and Spanish, but there was a blog that was available only in English but you had English and Spanish menus pointing to the same category, then you might end up with something like this:

  * ttp://www.example.com/en/blog/
  * ttp://www.example.com/es/blog/

This plugin would add a <link rel='canonical'... to the Spanish page(s). You select the blog directory and it ads the link to the category blog and any articles in that category.