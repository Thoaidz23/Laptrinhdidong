import '../services/api_service.dart';

class Order {
  final String id;
  final String status;
  final String date;
  final String time;
  final int total;
  final String image;
  final String item;
  final bool isPaid;

  // 👇 Bổ sung các trường từ backend
  final String name;
  final String phone;
  final String address;

  Order({
    required this.id,
    required this.status,
    required this.date,
    required this.time,
    required this.total,
    required this.image,
    required this.item,
    required this.isPaid,
    required this.name,
    required this.phone,
    required this.address,
  });

  factory Order.fromJson(Map<String, dynamic> json) {
    String fullDate = json['date'] ?? '';
    List<String> parts = fullDate.split(' ');
    String date = parts.isNotEmpty ? parts[0] : '';
    String time = parts.length > 1 ? parts[1] : '';

    return Order(
      id: json['code_order'] ?? '',
      status: parseStatus(json['status']),
      date: date,
      time: time,
      total: json['total_price'] ?? 0,
      image: buildImageUrl(json['image'] ?? ''),
      item: json['item'] ?? '',
      isPaid: (int.tryParse(json['paystatus']?.toString() ?? '0') ?? 0) == 1,
      name: json['name_user'] ?? '',
      phone: json['phone'] ?? '',
      address: json['address'] ?? '',
    );
  }

  static String parseStatus(dynamic status) {
    switch (status.toString()) {
      case '0':
        return 'Chờ xác nhận';
      case '1':
        return 'Đã xác nhận';
      case '2':
        return 'Đang vận chuyển';
      case '3':
        return 'Đã giao';
      case '4':
        return 'Đã hủy';
      default:
        return 'Không rõ';
    }
  }

  static String buildImageUrl(String rawImage) {
    if (rawImage.startsWith("http")) return rawImage;
    return '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads/$rawImage';
  }
}
