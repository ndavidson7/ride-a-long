Echo.private(`profile-pictures.${window.userId}`).listen(
    "ProfilePictureUploaded",
    () => {
        window.location.reload();
    }
);
