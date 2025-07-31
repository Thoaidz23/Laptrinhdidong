// services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../model/product.dart';
import '../model/order.dart';
import '../model/user.dart';
import '../model/cart_item.dart';
import '../model/about.dart';
import '../model/banner.dart';

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

        }
      } catch (e) {
        print(">>> Failed to fetch IP, using default: $e");
      }
    }

    static Future<List<Product>> fetchProducts({int? categoryId}) async {
      try {
        final url = categoryId != null
            ? "$baseUrl/get_products.php?category_id=$categoryId"
            : "$baseUrl/get_products.php";

        final response = await http.get(Uri.parse(url));

        if (response.statusCode == 200) {
          final List data = json.decode(response.body);
          return data.map((e) => Product.fromJson(e)).toList();
        } else {
          throw Exception("Lỗi server: ${response.statusCode}");
        }
      } catch (e) {
        print("Lỗi khi tải sản phẩm: $e");
        throw Exception("Không thể tải sản phẩm");
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


    static Future<Map<String, dynamic>> register(
        String name, String email, String password, String phone, String address) async {
      final response = await http.post(
        Uri.parse('$baseUrl/register.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'phone': phone,
          'address': address,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        print("Đăng ký response: $data");
        return data; // trả luôn message từ server
      } else {
        return {
          'status': false,
          'message': 'Lỗi kết nối server'
        };
      }
    }

    static Future<Map<String, dynamic>> changePassword({
      required int userId,
      required String currentPassword,
      required String newPassword,
    }) async {
      final url = Uri.parse('$baseUrl/change_password.php');
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'id_user': userId,
          'current_password': currentPassword,
          'new_password': newPassword,
        }),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        return {
          'status': false,
          'message': 'Lỗi máy chủ. Vui lòng thử lại.',
        };
      }
    }

    Future<void> sendOtp(String email) async {
      final res = await http.post(
        Uri.parse("$baseUrl/send_otp.php"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "email": email,
          "purpose": "forgot_password",
        }),
      );
      final data = jsonDecode(res.body);
      if (data['status']) {
        // điều hướng tới màn hình nhập OTP
      } else {
        // báo lỗi
      }
    }

    Future<bool> verifyOtp(String email, String otp) async {
      final res = await http.post(
        Uri.parse("$baseUrl/verify_otp.php"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "email": email,
          "otp": otp,
          "purpose": "forgot_password",
        }),
      );
      final data = jsonDecode(res.body);
      return data['status'] == true;
    }


    static Future<List<Order>> fetchOrders(int userId) async {
      final response = await http.get(Uri.parse("$baseUrl/get_user_orders.php?user_id=$userId"));

      print("PHẢN HỒI: ${response.body}");

      if (response.statusCode == 200) {
        try {
          final List data = json.decode(response.body);
          return data.map((e) => Order.fromJson(e)).toList();
        } catch (e) {
          print("Lỗi decode JSON: $e");
          throw Exception("Dữ liệu không đúng định dạng JSON");
        }
      } else {
        throw Exception("Không thể tải đơn hàng");
      }

    }


    static Future<bool> addToCart(int userId, int productId, int quantity, double price) async {
      final response = await http.post(
        Uri.parse("$baseUrl/add_to_cart.php"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          'id_user': userId,
          'id_product': productId,
          'quantity': quantity,
          'price': price,
        }),
      );

      final data = json.decode(response.body);
      print("🛒 addToCart response: $data");

      return data['status'] == true;
    }


    static Future<bool> placeOrder(
        int userId,
        List<Map<String, dynamic>> items,
        double total, {
          bool isBuyNow = false,
        }) async {
      final response = await http.post(
        Uri.parse("$baseUrl/create_order.php"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "user_id": userId,
          "items": items,
          "total_price": total,
          "isBuyNow": isBuyNow, // Gửi cờ "mua ngay"
        }),
      );
      final data = json.decode(response.body);
      return data['status'] == 'success';
    }

    Future<Map<String, dynamic>?> fetchOrderDetail(String codeOrder) async {
      final response = await http.get(Uri.parse('$baseUrl/get_order_detail.php?code_order=$codeOrder'));
      print('RESPONSE BODY: ${response.body}');
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['status'] == true) {
          return data;
        }
      }
      return null;
    }


    static Future<List<CartItem>> getCart(int userId) async {
      final response = await http.get(Uri.parse('$baseUrl/get_cart.php?id_user=$userId'));

      if (response.statusCode == 200) {
        final List<dynamic> jsonData = json.decode(response.body);
        return jsonData.map((json) => CartItem.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load cart');
      }
    }


    static Future<void> updateCart(int userId, int productId, int newQty) async {
      final url = Uri.parse('$baseUrl/update_cart.php');

      await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
        },
        body: json.encode({
          'id_user': userId,
          'id_product': productId,
          'quantity': newQty,
        }),
      );
    }

    static Future<void> deleteCartItem(int idUser, int idProduct) async {
      final url = Uri.parse('$baseUrl/delete_cart.php');

      try {
        final response = await http.post(
          url,
          body: jsonEncode({'id_user': idUser, 'id_product': idProduct}),
          headers: {'Content-Type': 'application/json'},
        );

        final result = jsonDecode(response.body);
        if (result['status'] == true) {
          print("🗑️ Xóa sản phẩm khỏi giỏ hàng thành công");
        } else {
          print("❌ Xóa thất bại: ${result['message']}");
        }
      } catch (e) {
        print('❗ Lỗi khi xóa giỏ hàng: $e');
      }
    }


    static Future<void> checkoutCart(int userId) async {
      await http.post(Uri.parse('$baseUrl/checkout_cart.php'), body: {
        'user_id': userId.toString(),
      });
    }

    static Future<List<Order>> fetchOrdersFromApi(int userId) async {
      final url = Uri.parse('$baseUrl/api_order.php'); // Đổi lại nếu file bạn khác tên

      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'id_user': userId}),
      );

      print("📦 fetchOrdersFromApi response: ${response.body}");

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        if (data['status'] == true && data['orders'] != null) {
          List ordersJson = data['orders'];
          return ordersJson.map((e) => Order.fromJson(e)).toList();
        } else {
          print("❌ Lỗi khi lấy đơn hàng: ${data['message']}");
          return [];
        }
      } else {
        throw Exception('❗ Lỗi kết nối server khi lấy đơn hàng');
      }
    }


    static Future<List<FooterItem>> fetchFooterItems() async {
      final response = await http.get(Uri.parse('$baseUrl/get_about.php'));
      if (response.statusCode == 200) {
        final jsonData = jsonDecode(response.body);
        if (jsonData['status'] == true && jsonData['data'] != null) {
          List<dynamic> list = jsonData['data'];
          return list.map((item) => FooterItem.fromJson(item)).toList();
        } else {
          return [];
        }
      } else {
        throw Exception('Failed to load footer items');
      }}
    Future<User?> fetchUserById(int id) async {
      final url = Uri.parse('$baseUrl/user.php?id=$id');
      final response = await http.get(url);

      print("User JSON: ${response.body}");

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        // Đảm bảo là Map<String, dynamic>, không phải List hoặc String
        if (data is Map<String, dynamic> && data.containsKey('id_user')) {
          return User.fromJson(data);
        } else {
          print("Không phải dữ liệu người dùng hợp lệ");
          return null;
        }
      } else {
        throw Exception('Failed to load user');

      }
    }

    static Future<bool> updateUser(User user) async {
      final response = await http.post(
        Uri.parse('$baseUrl/update_user.php'),
        body: {
          'id_user': user.id.toString(),
          'name': user.name,
          'email': user.email,
          'phone': user.phone,
          'address': user.address,
        },
      );
      return response.statusCode == 200 && response.body.contains('success');
    }

    Future<double?> _convertVNDToUSD(double vndAmount) async {
      const double vndPerEur = 27000; // Bạn có thể thay đổi tỉ lệ này nếu muốn động hơn
      final double eurAmount = vndAmount / vndPerEur;

      try {
        final url = Uri.parse('https://api.frankfurter.app/latest?amount=$eurAmount&from=EUR&to=USD');
        final response = await http.get(url);

        if (response.statusCode == 200) {
          final data = json.decode(response.body);
          final usdAmount = data['rates']['USD'];
          return usdAmount.toDouble();
        } else {
          throw Exception("Failed to convert currency");
        }
      } catch (e) {
        print("Currency conversion error: $e");
        return null;
      }
    }

    Future<void> startPaypalPayment(double vndAmount) async {
      // Convert sang USD
      double? usdAmount = await _convertVNDToUSD(vndAmount);

      if (usdAmount == null) {
        print("❌ Không thể chuyển đổi tiền tệ");
        return;
      }


      final response = await http.post(
        Uri.parse("http://10.0.2.2/ttsfood/api/create-payment.php"),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({"amount": usdAmount}),
      );

      final data = jsonDecode(response.body);

      if (data['approvalUrl'] != null) {
        final approvalUrl = data['approvalUrl'];

        // TODO: mở WebView tại đây
        print("✅ Open PayPal URL: $approvalUrl");

      } else {
        print("❌ Lỗi tạo payment: ${data['error']}");
      }
    }

    static Future<List<BannerModel>> fetchBanners() async {
      final response = await http.get(Uri.parse('$baseUrl/get_banners.php'));

      if (response.statusCode == 200) {
        final List jsonData = json.decode(response.body);
        return jsonData.map((item) => BannerModel.fromJson(item)).toList();
      } else {
        throw Exception('Failed to load banners');
      }
    }




}