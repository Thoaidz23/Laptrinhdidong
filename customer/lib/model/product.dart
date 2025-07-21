import '../services/api_service.dart';

class Product {
  final int id;
  final String name;
  final String content;
  final String image;
  final double price;
  final int id_category_product;
  Product({
    required this.id,
    required this.name,
    required this.content,
    required this.image,
    required this.price,
    required this.id_category_product,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    final image = json['image'] as String;
    return Product(
      id: int.parse(json['id_product'].toString()),
      name: json['name'],
      content: json['content'],
      image: image,
      price: double.parse(json['price'].toString()),
      id_category_product: int.parse(json['id_category_product'].toString()),
    );
  }

  String get imageUrl =>
      '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads/$image';

}
