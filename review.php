<!-- Author: Alex Spalvieri
     ID: 200403578 -->

<!-- The way to access this file is from a button in the footer -->
<?php session_start(); ?>
<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <section>
            <h2>Challenges</h2>
            <p>The only real challenge I faced while creating this website was trying to determine how I would allow only logged-in users
            to modify books. I ended up creating two separate pages, one with edit/delete functions and one without. Logged-in users would
            be given the modify page, vice versa. I used simple authentication to prevent non-logged-in users from getting to the modify page.</p>
        </section>
        <section>
            <h2>Successes</h2>
            <p>Creating the login and register system wasn't terribly hard. It had worked on the first rendition of it, which is good. I did
            end up finding a bunch of errors while testing the page, such as trying to search book names after submitting a book - this would
            cause a blank page to appear. I believe the website as it is, has no bugs or errors. If it does, they are very special cases that
            I could not find.</p>
        </section>
        <section>
            <h2>Next Steps</h2>
            <p>Changing the website to be ran as a MVC model would probably be the real next step. It is very time consuming to refactor a website,
            especially when it has a lot of code that depends on servicing through other pages. Past that, the only other real next step I could see
            adding would be more security within what the user is sending to the server.</p>
        </section>
    </div>
</main>
<?php require_once("footer.php"); ?>