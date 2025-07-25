import '../services/api_service.dart';

class BannerModel {
  final int id;
  final String image;

  BannerModel({
    required this.id,
    required this.image,
  });

  factory BannerModel.fromJson(Map<String, dynamic> json) {
    return BannerModel(
      id: int.parse(json['id_banner'].toString()),
      image: json['image'],
    );
  }

  String get imageUrl =>
      '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlybanner/uploads/$image';
}

