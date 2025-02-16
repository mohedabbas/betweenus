<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>
    <main class="container">
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
                <button class="button button--cta">.button--cta<img
                        src="..\..\assets\images\icons\picture.png"></button>
                <button class="button button--secondary">.button--secondary<img
                        src="..\..\assets\images\icons\picture.png"></button>
                <button class="button button--icon"><img src="..\..\assets\images\icons\picture.png"></button>
                <a href="#" class="button button--cta">No icon</a>
                <a href="#" class="button button--square">.button--square</a>
                <a href="#">Just a link</a>
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
                <img src="../../assets/images/sample_picture.png" alt="trip picture">
                <button class="photo-card__zoom"></button>
                <a href="#" class="photo-card__delete"></a>
            </div>
        </section>
        <section>
            <h2>.user-profile</h2>
            <div class="user-profile">
                <img src="../../assets/images/sample_avatar.png" alt="user profile picture">
                <p class="user-profile__name">John Doe</p>
            </div>
        </section>
        <section>
            <h2> Header</h2>
            <ul>
                <li>Just create a header component</li>
                <li>For the brand logo : create a link with class .brand</li>
                <li>For the Profile : create a link with class .user-profile</li>
            </ul>
            <header>
                <a href="/" class="brand">
                    <img src="../../assets/images/brand-logo.png" alt="brand logo">
                    BetweenUs
                </a>
                <a href="#" class="user-profile">
                    <img src="../../assets/images/sample_avatar.png" alt="user profile picture">
                    John Doe
                </a>
            </header>
        </section>
        <section>
            <h2>.grid</h2>
            <div class="grid">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="photo-card">
                        <img src="../../assets/images/sample_picture.png" alt="trip picture" class="photo-card__img">
                        <button class="photo-card__zoom"></button>
                        <a href="#" class="photo-card__delete"></a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="photo-card">
                        <img src="../../assets/images/sample_picture_2.png" alt="trip picture" class="photo-card__img">
                        <button class="photo-card__zoom"></button>
                        <a href="#" class="photo-card__delete"></a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="photo-card">
                        <img src="../../assets/images/sample_picture.png" alt="trip picture" class="photo-card__img">
                        <button class="photo-card__zoom"></button>
                        <a href="#" class="photo-card__delete"></a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="photo-card">
                        <img src="../../assets/images/sample_picture_2.png" alt="trip picture" class="photo-card__img">
                        <button class="photo-card__zoom"></button>
                        <a href="#" class="photo-card__delete"></a>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <h2>.gallery</h2>
            <a href="#" class="gallery">
                <div class="gallery__thumbnail__container">
                    <img src="../../assets/images/sample_picture.png" alt="">
                    <img src="../../assets/images/sample_picture_2.png" alt="">
                    <img src="../../assets/images/sample_picture.png" alt="">
                    <img src="../../assets/images/sample_picture_2.png" alt="">
                </div>
                <h3 class="gallery__name">Gallery name</h3>
            </a>
        </section>
    </main>

</body>