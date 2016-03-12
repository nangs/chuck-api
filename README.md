# CHUCKNORRIS.IO

JSON API for random [Chuck Norris jokes](https://api.chucknorris.io).

chucknorris.io is a free JSON API for hand curated Chuck Norris facts. If you think there is a fact missing drop us a line at admin@chucknorris.io

Chuck Norris facts are satirical factoids about martial artist and actor Chuck Norris that have become an Internet phenomenon and as a result have become widespread in popular culture. The 'facts' are normally absurd hyperbolic claims about Norris' toughness, attitude, virility, sophistication, and masculinity.

Chuck Norris facts have spread around the world, leading not only to translated versions, but also spawning localized versions mentioning country-specific advertisements and other Internet phenomena. Allusions are also sometimes made to his use of roundhouse kicks to perform seemingly any task, his large amount of body hair with specific regard to his beard, and his role in the action television series Walker, Texas Ranger.

## Usage

Retrieve a random chuck joke in JSON format:
```
GET https://api.chucknorris.io/jokes
```

Example response:
```json
{
    "id": "Enqz5wX3U_aTOW93Q9t10Q",
    "value": "Chuck Norris invented a language that incorporates karate and roundhouse kicks. So next time Chuck Norris is kicking your ass, don?t be offended or hurt, he may be just trying to tell you he likes your hat."
}
```