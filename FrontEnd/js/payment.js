var shoppingCart = (function () {
  cart = [];

  function Item(name, price, count, drugInfoId) {
    this.name = name;
    this.price = price;
    this.count = count;
    this.drugInfoId = drugInfoId;
  }

  // Save cart
  function saveCart() {
    localStorage.setItem("shoppingCart", JSON.stringify(cart));
  }

  // Load cart
  function loadCart() {
    cart = JSON.parse(localStorage.getItem("shoppingCart"));
  }
  if (localStorage.getItem("shoppingCart") != null) {
    loadCart();
  }

  var obj = {};

  // Add to cart
  obj.addItemToCart = function (name, price, count, id) {
    for (var item in cart) {
      if (cart[item].name === name) {
        cart[item].count++;
        saveCart();
        return;
      }
    }
    var item = new Item(name, price, count, id);
    cart.push(item);
    saveCart();
  };
  // Set count from item
  obj.setCountForItem = function (name, count) {
    for (var i in cart) {
      if (cart[i].name === name) {
        cart[i].count = count;
        break;
      }
    }
    saveCart();
  };
  // Set orderid from item
  obj.setOrderIdForItem = function (orderId) {
    for (var i in cart) {
      cart[i].orderId = orderId;
    }
    saveCart();
  };
  // Remove item from cart
  obj.removeItemFromCart = function (name) {
    for (var item in cart) {
      if (cart[item].name === name) {
        cart[item].count--;
        if (cart[item].count === 0) {
          cart.splice(item, 1);
        }
        break;
      }
    }
    saveCart();
  };

  // Remove all items from cart
  obj.removeItemFromCartAll = function (name) {
    console.log("remove", name);
    for (var item in cart) {
      if (cart[item].name === name) {
        cart.splice(item, 1);
        break;
      }
    }
    saveCart();
  };

  // Clear cart
  obj.clearCart = function () {
    cart = [];
    saveCart();
  };

  // Count cart
  obj.totalCount = function () {
    var totalCount = 0;
    for (var item in cart) {
      totalCount += cart[item].count;
    }
    return totalCount;
  };

  // Total cart
  obj.totalCart = function () {
    var totalCart = 0;
    for (var item in cart) {
      totalCart += cart[item].price * cart[item].count;
    }
    return Number(totalCart.toFixed(2));
  };

  // List cart
  obj.listCart = function () {
    var cartCopy = [];
    for (i in cart) {
      item = cart[i];
      itemCopy = {};
      for (p in item) {
        itemCopy[p] = item[p];
      }
      itemCopy.total = Number(item.price * item.count).toFixed(2);
      cartCopy.push(itemCopy);
    }
    return cartCopy;
  };
  return obj;
})();

function displayCart() {
  var cartArray = shoppingCart.listCart();
  var output = "";
  console.log("cartArray[i].name", cartArray);
  var orderCode;
  for (var i in cartArray) {
    orderCode = cartArray[0].orderId;
    output += `
        <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
                <h6 class="my-0">${cartArray[i].name}</h6>
            </div>
            <span class="text-muted">${cartArray[i].price}฿</span>
        </li>
      `;
  }
  
  $(".order-code").html(orderCode);
  $(".show-cart").html(output);
  $(".total-cart").html(`${shoppingCart.totalCart()}฿`);
  $(".total-count").html(shoppingCart.totalCount());
}

// Item count input
$(".show-count").on("change", ".item-count", function (event) {
  var name = $(this).data("name");
  var count = Number($(this).val());
  shoppingCart.setCountForItem(name, count);
  displayCart();
});
displayCart();
