# Twig parent taxonomy extension.
Drupal twig extension for parent taxonomy URL and name.

Use in theme implementation to display a taxonomy term (taxonomy-term.html.twig).

Example code:\
`<a href="{{term.id | parent_url }}">{{term.id | parent_name }}</a>`
