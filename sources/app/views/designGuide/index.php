<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body class="container">
    <h1>
        Design Guide
    </h1>
    <section>
        <h2>.button</h2>
        <p>Main class : <b>.button</b></p>
        <p>Class Modifier :</p>
        <ul>
            <li> <b>.button--cta</b> : orange background </li>
            <li> <b>.button--secondary</b> : grey background</li>
            <li> <b>.button--icon</b> : if icon only (adjust padding)</li>
        </ul>
        <p>the img tag for icon is in the button, no need to specify class</p>
        <div style="display:flex; gap: 0.8rem">
            <button class="button button--cta">.button--cta<img src="..\..\assets\images\icons\picture.png"></button>
            <button class="button button--secondary">.button--secondary<img
                    src="..\..\assets\images\icons\picture.png"></button>
            <button class="button button--icon"><img src="..\..\assets\images\icons\picture.png"></button>
            <a href="#" class="button button--cta">No icon</a>
            <a href="#" class="button button--square">.button--square</a>
        </div>
    </section>
    <section>
        <h2>.photo-card</h2>
        <ul>
            <li>Create a div with <b>.photo-card</b> class</li>
            <li>Add a img inside</li>
            <li>Add a button with <b>.photo-card__zoom</b> class inside</li>
            <li>Add a button with <b>.photo-card__delete</b> class inside</li>
        </ul>
        <div class="photo-card">
            <img src="../../assets/images/sample_picture.png" alt="picture">
            <button class="photo-card__zoom"></button>
            <button class="photo-card__delete"></button>
        </div>
    </section>
    <section>
        <h2>.user-profile</h2>
        <div class="user-profile">
            <img src="../../assets/images/sample_avatar.png" alt="">
            <p class="user-profile__name">John Doe</p>
        </div>
    </section>
</body>