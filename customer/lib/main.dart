import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'screen/main_screen.dart';
import 'screen/login_screen.dart';
import 'screen/register_screen.dart';
import 'model/user.dart';
import 'services/api_service.dart';
import 'provider/cart_provider.dart';
import 'screen/category_screen.dart'; // <-- nhớ import nếu chưa có

User? currentUser;

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ApiService.fetchBaseUrl(); // lấy IP từ get_ip.php nếu có

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'FastFood App',
      theme: ThemeData(
        primarySwatch: Colors.orange,
        fontFamily: 'Roboto',
      ),
      home: MainScreen(),
      routes: {
        '/login': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/category': (context) => const CategoryScreen(), // ✅ thêm dòng này
      },
    );
  }
}