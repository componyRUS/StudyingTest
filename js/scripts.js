document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartPriceSpan = document.querySelector('.cart-total-price');


    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');

            fetch('cart_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add&product_id=' + productId,
            })
            .then(response => response.text())
              .then(data => {
                if(data === "success"){
                  animateAddToCart(this);
                   updateCartCounter()
               }
                 console.log(data)
              })
            .catch(error => {
                console.error('Ошибка при добавлении в корзину:', error);
            });
        });
    });
    function animateAddToCart(button) {
        const cartIcon = document.querySelector('.cart-link');
        const buttonRect = button.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();
      const flyImg = document.createElement('img');
      const img = button.closest('.product').querySelector('img');
        if(img){
            flyImg.src = img.src;
           flyImg.classList.add('fly-image');
        document.body.appendChild(flyImg);
        flyImg.style.left = buttonRect.left + 'px';
        flyImg.style.top = buttonRect.top + 'px';
          const animation = flyImg.animate([
        { left: buttonRect.left + 'px', top: buttonRect.top + 'px' },
        { left: cartRect.left + 'px', top: cartRect.top + 'px',  opacity:0}
       ], { duration: 500, easing: 'ease-in' });
            animation.onfinish = () => {
            flyImg.remove();
          };
        }
   }
   function updateCartCounter() {
      fetch('cart_process.php', {
           method: 'POST',
            headers: {
             'Content-Type': 'application/x-www-form-urlencoded',
            },
           body: 'action=update_counter',
       }).then(response => response.json())
        .then(data => {
            if(cartPriceSpan){
                 cartPriceSpan.setAttribute('data-cart-price',data.totalPrice);
              }
           }).catch(error => {
               console.error('Ошибка при обновлении счетчика:', error);
           });
       }
});