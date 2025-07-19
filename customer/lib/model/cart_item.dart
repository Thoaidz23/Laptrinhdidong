class CartItem {
  final int idCart;
  final int idUser;
  final int idProduct;
  final int quantity;
  final double price;

  CartItem({
    required this.idCart,
    required this.idUser,
    required this.idProduct,
    required this.quantity,
    required this.price,
  });

  factory CartItem.fromJson(Map<String, dynamic> json) {
    return CartItem(
      idCart: int.parse(json['id_cart']),
      idUser: int.parse(json['id_user']),
      idProduct: int.parse(json['id_product']),
      quantity: int.parse(json['quantity']),
      price: double.parse(json['price']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id_cart': idCart.toString(),
      'id_user': idUser.toString(),
      'id_product': idProduct.toString(),
      'quantity': quantity.toString(),
      'price': price.toString(),
    };
  }
}
