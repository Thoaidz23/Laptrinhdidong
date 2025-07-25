import 'order.dart';

class OrderItem {
  final String name;
  final int quantity;
  final int price;
  final String image;

  OrderItem({
    required this.name,
    required this.quantity,
    required this.price,
    required this.image,
  });

  factory OrderItem.fromJson(Map<String, dynamic> json) {
    return OrderItem(
      name: json['name'] ?? '',
      quantity: json['quantity'] ?? 0,
      price: json['price'] ?? 0,
      image: Order.buildImageUrl(json['image'] ?? ''),
    );
  }
}
