# Instructions for Generating Classic Game Code with Overlays, Titles, Game Container, and Instructions Style

Generate the complete HTML code for classic games like Snake, Tetris, Pong, Pacman, Space Invaders, Asteroids, Breakout, etc., using HTML, CSS, and JavaScript. Follow these guidelines:

1. **Game Title Display:**
   - Display a unique and creative game title at the top center of the screen. The title should be short, engaging, and related to the game's theme.
   - Use CSS to ensure the title is always centered horizontally at the top of the screen. Example styles:
     ```css
     .game-title {
         position: absolute;
         top: 20px;
         left: 50%;
         transform: translateX(-50%);
         color: #fff;
         font-size: 24px;
         font-family: Arial, sans-serif;
         text-align: center;
         z-index: 10; /* Ensure the title stays above other elements */
     }
     ```

2. **Game Container or Canvas Boundaries:**
   - Ensure the game area (canvas or container) is always centered both horizontally and vertically on the screen.
   - Use CSS to add a border around the game area to distinguish it from the background.
   - Ensure the container or canvas aligns properly with the game's physics floor and walls.
   - Example styles for the game canvas or container:
     ```css
     .game-container {
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0 auto;
         border: 2px solid #fff; /* White border for visibility */
         width: 50%; /* Adjust width to fit well on the screen */
         height: 70vh; /* Adjust height to fit well on the screen */
         background-color: #000; /* Set a contrasting background */
         position: absolute; /* Absolute positioning to center the container */
         top: 50%; /* Center vertically */
         left: 50%; /* Center horizontally */
         transform: translate(-50%, -50%); /* Center the element */
         box-sizing: border-box; /* Ensure padding/border are included in the element's dimensions */
     }
     ```

3. **Instruction Overlay Box Style:**
   - Bottom-right corner: Add a semi-transparent box with a background color of `rgba(0, 0, 0, 0.7)`, padding of `10px`, and a border-radius of `5px`.
   - Use a simple, readable font (e.g., Arial, sans-serif) and font size of `14px` for the text.
   - Include headings in **bold** for "Mouse:" and "Keyboard:" with their corresponding instructions listed below each heading.
   - Keep the instructions minimal, concise, and direct. Clearly distinguish between **Mouse** and **Keyboard** actions.
   - Example styles:
     ```css
     .instructions {
         position: absolute;
         bottom: 20px;
         right: 20px;
         background: rgba(0, 0, 0, 0.7);
         padding: 10px;
         border-radius: 5px;
         font-size: 14px;
         color: #fff;
         font-family: Arial, sans-serif;
         z-index: 10; /* Ensure the instructions stay above other elements */
     }
     ```

4. **Examples of Instructions Format:**

   - **Snake:**
     ```
     Mouse:
     Move to control direction

     Keyboard:
     Arrow Keys to move
     Spacebar to pause
     ```

   - **Pong:**
     ```
     Mouse:
     Move Up/Down to control paddle

     Keyboard:
     W to move up
     S to move down
     ```

   - **Tetris:**
     ```
     Keyboard:
     Left/Right to move
     Up to rotate
     Down to drop
     ```

   - **Pacman:**
     ```
     Mouse:
     Move to control direction

     Keyboard:
     Arrow Keys to move
     Spacebar to pause
     ```

5. **Embedding Requirements:**
   - **All CSS and JavaScript** must be embedded directly within the same HTML file using `<style>` and `<script>` tags.
   - **All images** must be embedded using data URLs directly within the HTML. Avoid using external links or separate image files.

6. **Output Format:**
   - Your output must contain **only the HTML code** and should begin with `<html>` and end with `</html>`.
   - Add a comment at the beginning of the HTML code in the format: `<!--- game code: {game_name_snake_case} --->` where `game_name_snake_case` is the game title in snake_case format.
   - Do **not** use any formatting markers like "```html" or "```" for code blocks.
   - Provide the HTML code in a format ready to be used without requiring any additional modifications or external files.

7. **Accessibility and Security:**
   - Ensure all elements are accessible and dynamically sanitized to prevent XSS vulnerabilities.

Do not include any additional text or explanations like "Here’s a simple implementation..." or any similar description. The output should be strictly limited to the code itself, formatted as described.
