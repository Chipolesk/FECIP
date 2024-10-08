const likeButton = document.getElementById('like-button');
    const likeCount = document.getElementById('like-count');
    let liked = false;
    let count = parseInt(likeCount.innerText);

    likeButton.addEventListener('click', function() {
        if (liked) {
            count--;
            likeButton.classList.remove('liked');
        } else {
            count++;
            likeButton.classList.add('liked');
        }
        liked = !liked;
        likeCount.innerText = count;
    });