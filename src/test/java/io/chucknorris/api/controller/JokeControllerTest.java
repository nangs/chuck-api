package io.chucknorris.api.controller;

import static org.junit.Assert.assertEquals;
import static org.mockito.Mockito.times;
import static org.mockito.Mockito.verify;
import static org.mockito.Mockito.verifyNoMoreInteractions;
import static org.mockito.Mockito.when;

import io.chucknorris.api.exception.ResourceNotFoundException;
import io.chucknorris.api.model.Joke;
import io.chucknorris.api.repository.JokeRepository;
import java.util.Optional;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.MockitoJUnitRunner;

@RunWith(MockitoJUnitRunner.class)
public class JokeControllerTest {

  private static String jokeId = "ys--0t_-rrifz5jtcparbg";
  private static String jokeValue = "Some people ask for a Kleenex when they sneeze, Chuck Norris asks for a body bag.";
  private static Joke joke = new Joke()
      .setId(jokeId)
      .setValue(jokeValue);

  @InjectMocks
  private JokeController jokeController;

  @Mock
  private JokeRepository jokeRepository;

  @Test
  public void testGetCategories() {
    when(jokeRepository.findAllCategories()).thenReturn(
        new String[]{"dev", "animal"}
    );

    String[] categories = jokeController.getCategories();
    assertEquals("dev", categories[0]);
    assertEquals("animal", categories[1]);
    assertEquals(2, categories.length);

    verify(jokeRepository, times(1)).findAllCategories();
    verifyNoMoreInteractions(jokeRepository);
  }

  @Test
  public void testGetJokeReturnsJoke() {
    when(jokeRepository.findById(jokeId)).thenReturn(Optional.of(joke));

    Joke joke = jokeController.getJoke(jokeId);
    assertEquals(this.joke, joke);

    verify(jokeRepository, times(1)).findById(jokeId);
    verifyNoMoreInteractions(jokeRepository);
  }

  @Test(expected = ResourceNotFoundException.class)
  public void testGetJokeThrowsException() {
    when(jokeRepository.findById("does-not-exist")).thenThrow(new ResourceNotFoundException(""));

    jokeController.getJoke("does-not-exist");

    verify(jokeRepository, times(1)).findById("does-not-exist");
    verifyNoMoreInteractions(jokeRepository);
  }
}