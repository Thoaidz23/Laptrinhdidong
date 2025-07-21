// services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../model/product.dart';
import '../model/order.dart';
import '../model/user.dart';
import '../model/cart_item.dart';

class ApiService {
  static String baseUrl = "http://10.0.2.2/ttsfood/api"; // localhost for Android emulator

  // Gọi cái này khi app khởi động
  static Future<void> fetchBaseUrl() async {
    try {
      final response = await http.get(Uri.parse("http://10.0.2.2/ttsfood/api/get_ip.php"));
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        String ip = data['ip'];
        baseUrl = "http://$ip/ttsfood/api";
        print(">>> New baseUrl: $baseUrl");
      }
    } catch (e) {
      print(">>> Failed to fetch IP, using default: $e");
    }
  }

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

  Future<List<Product>> fetchProductsByCategory(int idCategory) async {
    final response = await http.get(Uri.parse('$baseUrl/product_by_category.php?id=$idCategory'));
    if (response.statusCode == 200) {
      List<dynamic> jsonData = json.decode(response.body);
      return jsonData.map((json) => Product.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load products by category');
    }
  }

  static Future<User?> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'email': email, 'password': password}),
    );

    final data = jsonDecode(response.body);
    print("Login response: $data");

    if (data['status'] == true) {
      return User.fromJson(data['user']);
    } else {
      return null;
    }
  }


  static Future<bool> register(String name, String email, String password, String phone) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'phone': phone,
      }),
    );

    final data = jsonDecode(response.body);
    print("Đăng ký response: $data");
    return data['status'] == true;
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

  static Future<bool> addToCart(int userId, int productId, int quantity, double price) async {
    final response = await http.post(
      Uri.parse("$baseUrl/add_to_cart.php"),
      body: {
        'id_user': userId.toString(),
        'id_product': productId.toString(),
        'quantity': quantity.toString(),
        'price': price.toString(),
      },
    );
    final data = json.decode(response.body);
    return data['status'] == 'success';
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

  static Future<List<CartItem>> getCart(int userId) async {
    final response = await http.get(Uri.parse('$baseUrl/cart.php?user_id=$userId'));
    if (response.statusCode == 200) {
      List data = jsonDecode(response.body);
      return data.map((item) => CartItem.fromJson(item)).toList();
    } else {
      throw Exception('Failed to load cart');
    }
  }

  static Future<void> updateCart(int userId, int productId, int newQty) async {
    await http.post(Uri.parse('$baseUrl/update_cart.php'), body: {
      'user_id': userId.toString(),
      'product_id': productId.toString(),
      'quantity': newQty.toString(),
    });
  }

  static Future<void> deleteCartItem(int userId, int productId) async {
    await http.post(Uri.parse('$baseUrl/delete_cart.php'), body: {
      'user_id': userId.toString(),
      'product_id': productId.toString(),
    });
  }

  static Future<void> checkoutCart(int userId) async {
    await http.post(Uri.parse('$baseUrl/checkout_cart.php'), body: {
      'user_id': userId.toString(),
    });
  }





}