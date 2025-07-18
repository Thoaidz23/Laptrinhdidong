// services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../model/product.dart';
import '../model/order.dart';
import '../model/user.dart';

class ApiService {
  static const String baseUrl = "http://192.168.211.1/ttsfood/api"; // localhost for Android emulator

  static Future<List<Product>> fetchProducts() async {
    final response = await http.get(Uri.parse("$baseUrl/get_products.php"));
    print(">>> Product API response: ${response.body}");

    if (response.statusCode == 200) {
      try {
        final List data = json.decode(response.body);
        return data.map((e) => Product.fromJson(e)).toList();
      } catch (e) {
        print(">>> JSON Decode Error: $e");
        throw Exception("Invalid JSON format");
      }
    } else {
      throw Exception("Failed to load products");
    }
  }


  static Future<User?> login(String email, String password) async {
    final response = await http.post(
      Uri.parse("$baseUrl/login.php"),
      body: {'email': email, 'password': password},
    );
    final data = json.decode(response.body);
    if (data['status'] == 'success') {
      return User.fromJson(data['user']);
    }
    return null;
  }

  static Future<bool> register(String name, String email, String password) async {
    final response = await http.post(
      Uri.parse("$baseUrl/register.php"),
      body: {
        'name': name,
        'email': email,
        'password': password,
      },
    );
    print(">>> RESPONSE BODY:");
    print(response.body); // 👈 in ra nội dung phản hồi thật

    try {
      final data = json.decode(response.body);
      return data['status'] == 'success';
    } catch (e) {
      print(">>> JSON Decode Error: $e");
      return false;
    }
  }


  static Future<List<Order>> fetchOrders(int userId) async {
    final response = await http.get(Uri.parse("$baseUrl/get_user_orders.php?user_id=$userId"));
    if (response.statusCode == 200) {
      final List data = json.decode(response.body);
      return data.map((e) => Order.fromJson(e)).toList();
    } else {
      throw Exception("Failed to load orders");
    }
  }

  static Future<bool> placeOrder(int userId, List<Map<String, dynamic>> items, double total) async {
    final response = await http.post(
      Uri.parse("$baseUrl/create_order.php"),
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "user_id": userId,
        "items": items,
        "total_price": total,
      }),
    );
    final data = json.decode(response.body);
    return data['status'] == 'success';
  }
}