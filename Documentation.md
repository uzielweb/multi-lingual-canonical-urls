# Theory of Operation #

In Joomla sites with multiple languages there is a possibility of duplicate content occurring.

For example, if you translate **most** of the content per language but don't translate all the content but still link to it for each language. In this scenario - if you desire to maintain menus in the user's language, then you will need to create a link or alias to the content - resulting in pages where the menus and modules are in one language and the main content is in another language.

A real-world example:
If you have a site in English and Spanish, but you have a company blog that is only in English. You could have the Spanish site link to the English blog, but then all the menus and modules would change to English. If you desire to keep these in Spanish then a new page will be created:
  * http://www.example.com/en/blog
  * http://www.example.com/es/blog
The problem is that Google will see these as two separate pages, but with the same content - creating duplicate content problems.

In order to solve these duplicate content issues, Google accepts a special link type in the `<head>` that declares a version the "canonical" or main version. See http://googlewebmastercentral.blogspot.com/2009/02/specify-your-canonical.html.

So the solution - in this example is to add the following link to the head of the Spanish page:

`<link rel="canonical" href="http://www.example.com/en/blog" />`

This would declare to Google that the English page is the authoritative page.

The problem is that Joomla doesn't have anything available currently that does this automatically. Joomla 1.7 and above does have Menu Associations which allow you to link menu items between languages.

This plugin allows you to set a canonical language and it will use the menu associations (when possible) to add a canonical link. If menu associations don't work then it will just change the link to the canonical language.

# Settings #

**Categories:** You select which categories you want to canonize. For example, if you have a blog category for ALL languages, you would select the blog category. The category page and all articles in the category would get the canonical URL.

**Domain:** If you were serving multiple international Top Level Domains (TLD) from the same server, then you would get duplicate content for each domain.

For example:
  * http://www.example.com/en/blog
  * http://www.example.com.br/pt/blog

Google would see these as two separate pages. By specifying the domain, it would add the canonical link on the Brazilian page.

**Language:** Select which language you want as the canonical language. If you have several languages, but the main content is in English, then set English as the canonical language.

## Warnings ##
I've only installed and tested this on a single site. It works for me. It doesn't mean it will work for you. Test this before deploying it to a production site.