import 'package:flutter/material.dart';
import '../widget/header.dart';

class CategoryScreen extends StatefulWidget {
  const CategoryScreen({super.key});

  @override
  State<CategoryScreen> createState() => _CategoryScreenState();
}

class _CategoryScreenState extends State<CategoryScreen> {
  final List<Map<String, dynamic>> products = [
    {
      'name': 'Trà sữa trân châu',
      'description': 'Trà sữa ngon, topping đầy đủ',
      'price': 35000,
      'category': 'Snack',
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
    },
    {
      'name': 'Cơm gà xối mỡ',
      'description': 'Gà chiên giòn, cơm mềm, thơm ngon',
      'price': 45000,
      'category': 'Cake',
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
    },
    {
      'name': 'Bánh mì thịt nướng',
      'description': 'Thịt nướng đậm vị, rau tươi',
      'price': 25000,
      'category': 'Kẹo',
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
    },
  ];

  final List<String> categories = [
    'Tất cả',
    'Snack',
    'Cake',
    'Kẹo',
    'Thức ăn đóng hộp',
    'Đồ ăn liền',
  ];

  String selectedCategory = 'Tất cả';

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;

    if (args != null && args['name'] != null && categories.contains(args['name'])) {
      selectedCategory = args['name'];
    }
  }

  @override
  Widget build(BuildContext context) {
    final filteredProducts = selectedCategory == 'Tất cả'
        ? products
        : products.where((p) => p['category'] == selectedCategory).toList();

    return Scaffold(
        backgroundColor: const Color(0xFFF5F5F5),
        body: Column(
            children: [
            // ✅ Header cố định trên cùng
            const Header(),

        // 🔙 Thanh tiêu đề + nút quay lại
        Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    decoration: const BoxDecoration(
    color: Colors.orange,
    boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4)],
    ),
    child: Row(
    children: [
    IconButton(
    icon: const Icon(Icons.arrow_back, color: Colors.white),
    onPressed: () => Navigator.pop(context),
    ),
    const Expanded(
    child: Center(
    child: Text(
    'Danh mục sản phẩm',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.white,
    ),
    ),
    ),
    ),
    const SizedBox(width: 48),
    ],
    ),),

    // 📌 Thanh filter danh mục
    Container(
    height: 42,
    margin: const EdgeInsets.only(top: 8),
    child: SingleChildScrollView(
    scrollDirection: Axis.horizontal,
    child: Row(
    children: categories.map((category) {
    final isActive = category == selectedCategory;

    return GestureDetector(
    onTap: () {
    setState(() => selectedCategory = category);
    },
    child: Container(
    padding: const EdgeInsets.symmetric(horizontal: 16),
    alignment: Alignment.center,
    decoration: BoxDecoration(
    border: Border(
    bottom: BorderSide(
    color: isActive ? Colors.orange : Colors.transparent,
    width: 3,
    ),
    ),
    ),
    child: Text(
    category,
    style: TextStyle(
    fontSize: 13,
    fontWeight: isActive ? FontWeight.bold : FontWeight.normal,
    color: isActive ? Colors.orange : Colors.black,
    ),
    ),
    ),
    );
    }).toList(),
    ),
    ),
    ),

    const SizedBox(height: 10),

    // 🧾 Danh sách sản phẩm
    Expanded(
    child: ListView.builder(
    padding: const EdgeInsets.all(16),
    itemCount: filteredProducts.length,
    itemBuilder: (context, index) {
    final product = filteredProducts[index];
    return Container(
    margin: const EdgeInsets.only(bottom: 12),
    padding: const EdgeInsets.all(12),
    decoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.circular(12),
    boxShadow: const [
    BoxShadow(
    color: Colors.black12,
    blurRadius: 6,
    offset: Offset(0, 2),
    ),
    ],
    ),
    child: Row(
    children: [
    ClipRRect(
    borderRadius: BorderRadius.circular(8),
    child: Image.network(
    product['image'],
    width: 60,
    height: 60,
    fit: BoxFit.cover,
    ),
    ),
    const SizedBox(width: 12),
    Expanded(
    child: Column(crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          product['name'],
          style: const TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          product['description'],
          style: const TextStyle(fontSize: 13),
        ),
        const SizedBox(height: 4),
        Text(
          "Giá: ${product['price']}đ",
          style: const TextStyle(color: Colors.green),
        ),
      ],
    ),
    ),
    ],
    ),
    );
    },
    ),
    ),
            ],
        ),
    );
  }
}