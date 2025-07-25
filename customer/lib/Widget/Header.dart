import 'package:flutter/material.dart';
import '../screen/account_screen.dart';
import '../model/user.dart';

class Header extends StatelessWidget {
  const Header({super.key});

  String getFirstName(String fullName) {
    return fullName.trim().split(' ').last;
  }

  @override
  Widget build(BuildContext context) {
    final name = currentUser != null ? getFirstName(currentUser!.name) : '';

    return Container(
      padding: const EdgeInsets.fromLTRB(16, 40, 16, 14),
      decoration: BoxDecoration(
        border: Border(
          bottom: BorderSide(color: Colors.grey.shade300),
        ),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Text(
            'TTSFood',
            style: TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: Colors.red,
            ),
          ),
          Row(
            children: [
              if (name.isNotEmpty)
                Padding(
                  padding: const EdgeInsets.only(right: 8.0),
                  child: Text(
                    name,
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              GestureDetector(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const AccountPage()),
                  );
                },
                child: const Icon(Icons.account_circle, size: 38),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
