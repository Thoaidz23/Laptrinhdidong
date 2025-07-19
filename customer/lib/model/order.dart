
// models/order.dart
class Order {
  final int id;
  final double totalPrice;
  final String orderDate;
  final int status;

  Order({
    required this.id,
    required this.totalPrice,
    required this.orderDate,
    required this.status,
  });

  factory Order.fromJson(Map<String, dynamic> json) {
    return Order(
      id: int.parse(json['id'].toString()),
      totalPrice: double.parse(json['total_price'].toString()),
      orderDate: json['order_date'],
      status: int.parse(json['status'].toString()),
    );
  }
}