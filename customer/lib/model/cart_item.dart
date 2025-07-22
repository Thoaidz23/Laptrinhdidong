import '../services/api_service.dart';

class CartItem {
  final int idCart;
  final int idUser;
  final int idProduct;
  final int quantity;
  final double price;
  final String name;
  final String image;

  CartItem({
    required this.idCart,
    required this.idUser,
    required this.idProduct,
    required this.quantity,
    required this.price,
    required this.name,
    required this.image,
  });

  factory CartItem.fromJson(Map<String, dynamic> json) {
    return CartItem(
      idCart: int.parse(json['id_cart'].toString()),
      idUser: int.parse(json['id_user'].toString()),
      idProduct: int.parse(json['id_product'].toString()),
      quantity: int.parse(json['quantity'].toString()),
      price: double.parse(json['price'].toString()),
      name: json['name'],
      image: json['image'],
    );
  }

  String get imageUrl =>
      '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads/$image';
}
