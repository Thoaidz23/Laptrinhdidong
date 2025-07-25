import 'package:flutter/material.dart';
import 'package:ttsfood/Widget/CategorySection.dart';
import '../widget/header.dart';
import '../widget/BannerSlider.dart';
import '../widget/ProductGrid.dart';
import 'search_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: Colors.white,
        body: Column(
            children: [
            const Header(), // ✅ Luôn nằm trên cùng

        Expanded(
        child: SingleChildScrollView(
        child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
    // 🔍 Thanh tìm kiếm
    Padding(
    padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8),
    child: Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
    const Text(
    'Bạn muốn ăn gì nào?',
    style: TextStyle(
    fontSize: 18,
    fontWeight: FontWeight.bold,
    color: Colors.black,
    ),
    ),
    const SizedBox(height: 8),
    Container(
    decoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.circular(30),
    boxShadow: [
    BoxShadow(
    color: Colors.black12,
    blurRadius: 6,
    offset: Offset(0, 2),
    ),
    ],
    ),
    child: TextField(
    controller: _searchController,
    onSubmitted: (value) {
    if (value.trim().isNotEmpty) {
    Navigator.push(
    context,
    MaterialPageRoute(
    builder: (context) => SearchScreen(keyword: value),
    ),
    );
    }
    },
    decoration: InputDecoration(
    hintText: 'Tìm món ăn, cửa hàng...',
    hintStyle: TextStyle(color: Colors.grey[500]),
    prefixIcon: Icon(Icons.search, color: Colors.grey[600]),border: InputBorder.none,
    contentPadding: const EdgeInsets.symmetric(vertical: 14, horizontal: 20),
    ),
    ),
    ),
    ],
    ),
    ),

    const BannerSlider(),
    const SizedBox(height: 20),
    const CategorySection(),
    const SizedBox(height: 20),

    const Padding(
    padding: EdgeInsets.symmetric(horizontal: 16.0),
    child: Text(
    'MÓN ĂN ĐƯỢC YÊU THÍCH NHẤT',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),
    ),
    ),
    const SizedBox(height: 10),
    ProductGrid(),

    const SizedBox(height: 10),
    const Padding(
    padding: EdgeInsets.symmetric(horizontal: 16.0),
    child: Text(
    'SNACK',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),
    ),
    ),
    ProductGrid(idCategoryProduct: 1),

    const SizedBox(height: 10),
    const Padding(
    padding: EdgeInsets.symmetric(horizontal: 16.0),
    child: Text(
    'BÁNH',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),
    ),
    ),
    ProductGrid(idCategoryProduct: 2),

    const SizedBox(height: 10),
    const Padding(
    padding: EdgeInsets.symmetric(horizontal: 16.0),
    child: Text(
    'KẸO',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),
    ),
    ),
    ProductGrid(idCategoryProduct: 3),

    const SizedBox(height: 10),
    const Padding(
    padding: EdgeInsets.symmetric(horizontal: 16.0),
    child: Text(
    'THỨC UỐNG ĐÓNG HỘP',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),),
    ),
          ProductGrid(idCategoryProduct: 4),

          const SizedBox(height: 10),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16.0),
            child: Text(
              'ĐỒ ĂN ĐÓNG HỘP',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Colors.deepOrange,
              ),
            ),
          ),
          ProductGrid(idCategoryProduct: 5),

          const SizedBox(height: 10),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16.0),
            child: Text(
              'ĐỒ ĂN LIỀN',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
                color: Colors.deepOrange,
              ),
            ),
          ),
          ProductGrid(idCategoryProduct: 6),

          const SizedBox(height: 20),
        ],
        ),
        ),
        ),
            ],
        ),
    );
  }
}