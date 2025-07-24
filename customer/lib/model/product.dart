import '../services/api_service.dart';

class Product {
  final int id;
  final String name;
  final String content;
  final String image; // ảnh đại diện
  final double price;
  final int id_category_product;
  final List<ProductImage> images; // danh sách ảnh chi tiết

  Product({
    required this.id,
    required this.name,
    required this.content,
    required this.image,
    required this.price,
    required this.id_category_product,
    required this.images,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    final image = json['image'] as String;
    final List<ProductImage> images = (json['images'] as List<dynamic>?)
        ?.map((e) => ProductImage.fromJson(e))
        .toList() ??
        [];

    return Product(
      id: int.parse(json['id_product'].toString()),
      name: json['name'],
      content: json['content'],
      image: image,
      price: double.parse(json['price'].toString()),
      id_category_product: int.parse(json['id_category_product'].toString()),
      images: images,
    );
  }

  // ảnh đại diện
  String get imageUrl =>
      '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads/$image';
}

// Model ảnh phụ (ảnh chi tiết)
class ProductImage {
  final int id;
  final String name;

  ProductImage({
    required this.id,
    required this.name,
  });

  factory ProductImage.fromJson(Map<String, dynamic> json) {
    return ProductImage(
      id: int.parse(json['id_product_images'].toString()),
      name: json['name'],
    );
  }

  String get fullUrl =>
      '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads_chitiet/$name';
}