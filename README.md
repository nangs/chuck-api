# CHUCKNORRIS.IO

JSON API for random [Chuck Norris jokes](https://api.chucknorris.io).

[chucknorris.io](https://api.chucknorris.io) is a free JSON API for hand curated Chuck Norris facts.

Chuck Norris facts are satirical factoids about martial artist and actor Chuck Norris that have become an Internet
phenomenon and as a result have become widespread in popular culture. The 'facts' are normally absurd hyperbolic claims
about Norris' toughness, attitude, virility, sophistication, and masculinity.

Chuck Norris facts have spread around the world, leading not only to translated versions, but also spawning localized
versions mentioning country-specific advertisements and other Internet phenomena. Allusions are also sometimes made to
his use of roundhouse kicks to perform seemingly any task, his large amount of body hair with specific regard to his
beard, and his role in the action television series Walker, Texas Ranger.

## Usage

Retrieve a random chuck joke in JSON format:
```
$ curl --request GET \
       --url 'https://api.chucknorris.io/jokes/random?category=dev' \
       --header 'content-type: application/json'
```

Example response:
```json
{
    category : [
        "dev"
    ],
    icon_url : "https://assets.chucknorris.host/img/avatar/chuck-norris.png",
    id       : "ye0_hnd3rgq68e_pfvsqqg",
    url      : "https://127.0.0.1:8080/jokes/ye0_hnd3rgq68e_pfvsqqg",
    value    : "Chuck Norris can instantiate an abstract class."
}
```

## License

This distribution is covered by the **GNU GENERAL PUBLIC LICENSE**, Version 3, 29 June 2007. 

## Support & Contact

Having trouble with this repository? Check out the documentation at the repository's site or contact m@matchilling.com and we’ll help you sort it out.

Happy Coding

:v:
