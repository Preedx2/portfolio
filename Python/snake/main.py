"""
Simple snake clone made using pygame
I decided to make "smooth" snake because I think it is harder
As you score more points the snake gets faster until your score is equal to 30

Author: Patryk Hernas, May 2023
"""
import pygame
import random

_width = 640      # Screen width
_height = 480     # Screen height
_size = 30        # size of snake segments and apples
_font_size = 40
_growth_rate = 3  # how many segments grow after eating apple
_fps = 120


def reset_variables() -> None:
    """
    Function for assigning initial values to all the game variables
    :return: None
    """
    global speed, score, segments, timer
    global game_over_flag, moving_flag, apple_present
    global direction
    global prev_pos, player_pos
    player_pos = pygame.Vector2(_width // 2, _height // 2)  # player starts at center
    game_over_flag = False  # is the game over?
    moving_flag = False     # is snake moving?
    apple_present = False   # is apple present?
    speed = 3       # starting speed
    score = 0       # starting score
    segments = 6    # starting segments (Note: best if at least 6)
    timer = 0       # resetting timer
    direction = (0, 0)  # direction of movement, (0,0) is stationary
    prev_pos = [pygame.Vector2(-30, -30) for i in range(segments)]  # tab of positions


def main():
    global speed, score, segments, timer
    global game_over_flag, moving_flag, apple_present
    global direction
    global prev_pos, player_pos

    reset_variables()
    pygame.init()
    screen = pygame.display.set_mode((_width, _height))
    play_area = pygame.Rect(_size//2, _size//2, _width - _size, _height - _size)
    clock = pygame.time.Clock()
    font = pygame.font.Font(None, _font_size)
    head = pygame.Rect(0, 0, _size, _size)
    apple = head.copy()
    running = True

    while running:
        # ================   player inputs   ====================
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False
            if event.type == pygame.KEYDOWN and game_over_flag and timer > 500:
                reset_variables()   # reset after game over (after atleast 0.5 s has passed)

        keys = pygame.key.get_pressed()
        if not moving_flag and (keys[pygame.K_w] or keys[pygame.K_s]
                                or keys[pygame.K_a] or keys[pygame.K_d]):
            moving_flag = True

        if keys[pygame.K_w]:
            if direction != (0, 1):
                direction = (0, -1)
        if keys[pygame.K_s]:
            if direction != (0, -1):
                direction = (0, 1)
        if keys[pygame.K_a]:
            if direction != (1, 0):
                direction = (-1, 0)
        if keys[pygame.K_d]:
            if direction != (-1, 0):
                direction = (1, 0)

        # ================   logic   =======================
        dt = clock.get_time()
        dt *= 0.06

        player_pos += speed * dt * pygame.Vector2(direction)
        head.center = player_pos

        if not play_area.collidepoint(player_pos) and moving_flag:  # screen collision
            game_over_flag = True

        segments_to_draw = []       # snake segments left behind the head
        for i in range(segments):
            segment = pygame.Rect.copy(head)
            segment.center = prev_pos[-(1 + i)]  # we traverse list in reverse
            segments_to_draw.append(segment)
            if i > 4 and segment.colliderect(head) and moving_flag:
                game_over_flag = True  # first 5 segments not checked for collision

        while not apple_present:
            apple_pos = pygame.Vector2(random.randint(_size, _width-_size),
                                       random.randint(_size, _height-_size))
            apple.center = apple_pos    # randomly choose position for apple
            if apple.collidelist(segments_to_draw) == -1 and not apple.colliderect(head):
                apple_present = True    # do not exit loop until valid apple is present

        if apple.colliderect(head):  # after eating the apple
            score += 1
            segments += _growth_rate
            for i in range(_growth_rate):
                prev_pos.insert(0, prev_pos[0])
            if speed < 5.9:
                speed += 0.1  # gradually increase speed as score goes up
            apple_present = False

        if timer > 60 and moving_flag:
            del prev_pos[0]  # modify list of segments every 60 ms
            prev_pos.append(player_pos.copy())
            timer = 0

        if game_over_flag:
            direction = (0, 0)
            moving_flag = False
            player_pos = pygame.Vector2(-30, -30)

        # ==============   rendering   ==============
        # order of drawing is important, stuff lower down will be drawn on top
        screen.fill("black")
        pygame.display.set_caption(f"Wunsz | score: {score} | speed: {speed:.1f}")

        for segment in segments_to_draw:
            pygame.draw.rect(screen, (10, 128, 20), segment)
        pygame.draw.rect(screen, "red", apple)
        pygame.draw.rect(screen, "green", head)

        if game_over_flag:
            # displaying game over text and score
            text1 = font.render("Game Over !!!", True, "white", "black")
            text2 = font.render(f"Score: {score}", True, "white", "black")
            text3 = font.render("Press any key to restart", True, "white", "black")
            text1_rect = text1.get_rect()
            text2_rect = text2.get_rect()
            text3_rect = text3.get_rect()
            text1_rect.center = (_width//2, _height//2 - _font_size)
            text2_rect.center = (_width//2, _height//2)
            text3_rect.center = (_width//2, _height//2 + _font_size)
            screen.blit(text1, text1_rect)
            screen.blit(text2, text2_rect)
            screen.blit(text3, text3_rect)

        pygame.display.flip()
        clock.tick(_fps)  # game logic is independent of frame rate
        timer += int(dt * 100 // 6)
        # print(f"\rFPS: {clock.get_fps():4.0f}\ttimer: {timer:5.0f}\ttick rate: {dt:3.3f}", end='')  # for testing

    pygame.quit()


if __name__ == "__main__":
    main()
