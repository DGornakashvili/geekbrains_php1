$(document).ready(() => {
  $('body').on('click', 'button', e => {
    e.preventDefault();
    let $id = e.target.dataset.id;
    let $btn = e.target.className;
    let $cart = $('.cart');
    let $cartInfo = $('.cart-total-order');

    if ($btn === 'add-btn') {
      $.post({
        url: '/productApi.php',
        data: {
          'id': $id,
          'method': 'add',
        },
        success: result => {
          if (!result.error) {
            if (result.type === 'create') {
              if ($cart.find('i')) {
                $cart.find('i').remove();
              }
              $cartInfo.before(result.data);
            } else if (result.type === 'update') {
              $(`.cart-item[data-id=${$id}]`).find('span').html(result.data);
            }
            $('.cart-subtotal').html(result.subtotal);
          }
        }
      });
    } else if ($btn === 'remove-btn') {
      $.post({
        url: '/productApi.php',
        data: {
          'id': $id,
          'method': 'remove',
        },
        success: result => {
          $(`.cart-item[data-id=${$id}]`).remove();
          $('.cart-subtotal').html(result.subtotal);
          if (!$('.cart-item').length) {
            $cartInfo.before('<i>Cart is empty</i>');
          }
        },
      });
    } else if ($btn === 'signIn-btn') {
      const $uLogin = $('input[name="login"]').val();
      const $uPass = $('input[name="password"]').val();

      $.post({
        url: '/productApi.php',
        data: {
          'uLogin': $uLogin,
          'uPass': $uPass,
          'method': 'signIn',
        },
        success: result => {
          if (!result.error) {
            $('.signIn-form').html(result.data);
          } else {
            let $error = $('.signError');
            if (!$error.length) {
              $('.signIn-form').prepend(result.data);
            } else {
              $error.html(result.data);
            }
          }
        },
      });
    } else if ($btn === 'signUp-btn') {
      const $uName = $('input[name="name"]').val();
      const $uLogin = $('input[name="login"]').val();
      const $uPass = $('input[name="password"]').val();

      $.post({
        url: '/productApi.php',
        data: {
          'uName': $uName,
          'uLogin': $uLogin,
          'uPass': $uPass,
          'method': 'signUp',
        },
        success: result => {
          if (!result.error) {
            $('.signUp-form').html(result.data);
          } else {
            let $error = $('.signError');
            if (!$error.length) {
              $('.signUp-form').prepend(result.data);
            } else {
              $error.html(result.data);
            }
          }
        },
      });
    } else if ($btn === 'order-btn') {
      if (!$('.cart-item').length) {
        alert('No products to order!');
        exit();
      }

      $.post({
        url: '/productApi.php',
        data: {
          'method': 'order',
        },
        success: result => {
          if (!result.error) {
            $('.cart-item').each((i, e) => e.remove());
            $('.cart-subtotal').html(result.subtotal);
            $cartInfo.before('<i>Cart is empty</i>');
          }
        },
      });
    } else if ($btn === 'cancel-btn') {
      $.post({
        url: '/productApi.php',
        data: {
          'method': 'cancel',
          'id': $id,
        },
        success: result => {
          if (!result.error) {
            const $order = $(`.orders-item[data-id=${$id}]`);

            $order.find('.order-status').html(result.type);
            $order.find(`.${$btn}`).replaceWith(result.data);
          }
        },
      });
    } else if ($btn === 'status-btn') {
      const $status = $(`.orders-item[data-id=${$id}]`).find('.order-status').find('input');
      $.post({
        url: '/productApi.php',
        data: {
          'method': 'update-status',
          'id': $id,
          'status': $status.val(),
        },
        success: result => {
          if (!result.error) {
            alert(result.data);
          }
        },
      });
    }
  });
});