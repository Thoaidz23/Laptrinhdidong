import 'package:flutter/material.dart';
import 'package:ttsfood/Widget/CategorySection.dart';
import '../model/product.dart';
import '../widget/header.dart';
import '../widget/MenuBar.dart';
import '../widget/BannerSlider.dart';
import '../widget/ProductGrid.dart';

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
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Header(),

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
                        decoration: InputDecoration(
                          hintText: 'Tìm món ăn, cửa hàng...',
                          hintStyle: TextStyle(color: Colors.grey[500]),
                          prefixIcon: Icon(Icons.search, color: Colors.grey[600]),
                          border: InputBorder.none,
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
              // sẽ hiển thị tất cả sản phẩm có category // nếu muốn hiển thị toàn bộ
                // Hiển thị chỉ sản phẩm danh mục có id = 2

              const SizedBox(height: 20),
              ProductGrid(idCategoryProduct: 1),
            ],
          ),
        ),
      ),
    );
  }

}
