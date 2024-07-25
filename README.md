# PHP Link Tracker

A simple PHP-based link tracking system that allows users to add, edit, delete, and track the number of clicks on various links. This project features a responsive design with a form for adding new links and a table displaying the existing links and their click counts.

## Features

- **Add Links**: Add new links with a title and URL.
- **Edit Links**: Edit existing links to update the title or URL.
- **Delete Links**: Remove links from the database.
- **Track Clicks**: Count and display the number of clicks for each link.
- **Copy Link**: Copy the tracker URL to share with others.
- **Responsive Design**: The layout is responsive and adjusts for different screen sizes.

## Technologies Used

- **PHP**: Server-side scripting language for backend logic.
- **MySQL**: Database management system for storing link data.
- **HTML/CSS**: Markup and styling for the front-end interface.
- **JavaScript**: Client-side scripting for copy-to-clipboard functionality.

## Installation

1. **Clone the repository**:
    ```sh
    git clone https://github.com/yourusername/link-tracker.git
    cd link-tracker
    ```

2. **Set up the database**:
    - Create a MySQL database and import the provided `database.sql` file.
    - Update the database configuration in `config/config.php`:
    ```php
    return [
        'host' => 'localhost',
        'username' => 'your_username',
        'password' => 'your_password',
        'database' => 'your_database',
    ];
    ```

3. **Run the application**:
    - Make sure you have a local server running (like WAMP, XAMPP, or MAMP).
    - Place the project in your server's root directory (e.g., `www` for WAMP, `htdocs` for XAMPP).
    - Open your browser and navigate to `http://localhost/link-tracker`.

## Usage

1. **Add a Link**:
    - Fill in the title and URL in the form on the left.
    - Click the "Add Link" button to save the link.

2. **Edit a Link**:
    - Click the "Edit" button next to the link you want to edit.
    - Update the title or URL and save the changes.

3. **Delete a Link**:
    - Click the "Delete" button next to the link you want to remove.

4. **Track Clicks**:
    - Click on the link title to increment the click count.
    - The click count will update in real-time.

5. **Copy Link**:
    - Click the "Copy Link" link to copy the tracker URL to your clipboard.
    - Share the copied URL to track clicks from different users.

## License

This project is licensed under the MIT License.

