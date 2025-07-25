import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'dart:io';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:webview_flutter_android/webview_flutter_android.dart';

import 'screen/main_screen.dart';
import 'screen/login_screen.dart';
import 'screen/register_screen.dart';
import 'model/user.dart';
import 'services/api_service.dart';

import 'provider/cart_provider.dart';
import 'screen/changePassword.dart'; // thêm import nếu chưa có
import 'screen/category_screen.dart'; // <-- nhớ import nếu chưa có


User? currentUser;

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await ApiService.fetchBaseUrl();


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
        '/change-password': (context) => const ChangePasswordPage(),
        '/register': (context) => const RegisterScreen(),
        '/category': (context) => const CategoryScreen(), // ✅ thêm dòng này
      },
    );
  }
}