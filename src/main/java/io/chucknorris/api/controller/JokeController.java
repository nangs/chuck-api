package io.chucknorris.api.controller;

import io.chucknorris.api.model.Joke;
import io.chucknorris.api.repository.JokeRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class JokeController {

  @Autowired
  private JokeRepository jokeRepository;

  @Autowired
  public JokeController(JokeRepository jokeRepository) {
    this.jokeRepository = jokeRepository;
  }

  @RequestMapping(
      value = "/jokes/categories",
      method = RequestMethod.GET,
      consumes = MediaType.APPLICATION_JSON_UTF8_VALUE,
      produces = MediaType.APPLICATION_JSON_UTF8_VALUE
  )
  public @ResponseBody String[] getCategories() {
    return jokeRepository.findAllCategories();
  }

  @RequestMapping(
      value = "/jokes/{id}",
      method = RequestMethod.GET,
      consumes = MediaType.APPLICATION_JSON_UTF8_VALUE,
      produces = MediaType.APPLICATION_JSON_UTF8_VALUE
  )
  public @ResponseBody
  Joke getJoke(@PathVariable String id) {
    return jokeRepository.findById(id).get();
  }
}
