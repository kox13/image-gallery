<script>
    const message = document.querySelector('#messageContainer');

    if(message != null){
        setTimeout(() => {
            message.style.top = '-100%';

            setTimeout(() => {
                message.remove();
            }, 1000);
        }, 3000);
    }

    console.log('aaa');
</script>