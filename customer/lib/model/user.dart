class User {
  final int id;
  final String name;
  final String email;
  final String phone;
  final String address;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.address,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    print("User JSON: $json"); // debug kiểm tra

    return User(
      id: int.tryParse(json['id_user']?.toString() ?? '0') ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'] ?? '',
      address: json['address'] ?? '',
    );
  }

}

// Biến toàn cục để lưu người dùng hiện tại
User? currentUser;
