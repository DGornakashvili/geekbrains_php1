$(document).ready(() => {
  $('body').on('click', 'button', e => {
    e.preventDefault();
    let $id = e.target.dataset.id;
    let $btn = e.target.className;
    let $cart = $('.cart');

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
              if (!$cart.find('div').length) {
                $cart.html(result.data);
              } else {
                $cart.append(result.data);
              }
            } else if (result.type === 'update') {
              $(`.cart-item[data-id=${$id}]`).find('span').html(result.data);
            }
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
        success: () => {
          $(`.cart-item[data-id=${$id}]`).remove();
          if (!$cart.html().includes('div')) {
            $cart.html('Cart is empty');
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
            if (!$('.signError').length) {
              $('.signIn-form').prepend(result.data);
            } else {
              $('.signError').html(result.data);
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
            if (!$('.signError').length) {
              $('.signUp-form').prepend(result.data);
            } else {
              $('.signError').html(result.data);
            }
          }
        },
      });
    }
  });
});