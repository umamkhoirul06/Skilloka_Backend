import re

path = r'c:\PROYEK 2\Skilloka_Backend\resources\views\admin\courses\create.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace label classes
content = re.sub(r'class="block text-sm font-medium text-gray-900 mb-1"', r'class="block text-sm font-medium text-gray-800 mb-1"', content)
content = re.sub(r'class="text-sm text-gray-900"', r'class="text-sm text-gray-800"', content)

# Replace input classes
content = re.sub(r'class="text-gray-900 placeholder-gray-400"', r'class="bg-white text-gray-900 placeholder-gray-400 border-gray-300 w-full p-2.5 rounded-lg border"', content)
content = re.sub(r'class="text-gray-900"', r'class="bg-white text-gray-900 border-gray-300 w-full p-2.5 rounded-lg border"', content)

# Remove the inline styles for inputs to prevent conflict
content = re.sub(r'\s*style="\s*width:100%;\s*padding:10px;\s*border:1px solid #ddd;\s*border-radius:6px\s*"', '', content)

# Make sure heading is text-gray-900
content = re.sub(r'<h2 style="font-size:18px;font-weight:600;margin-bottom:20px">', r'<h2 class="text-gray-900" style="font-size:18px;font-weight:600;margin-bottom:20px">', content)
content = re.sub(r'<h2 style="\s*font-size:18px;\s*font-weight:600;\s*margin-bottom:20px\s*">', r'<h2 class="text-gray-900" style="font-size:18px;font-weight:600;margin-bottom:20px">', content)

# Make table headers and cells dark
content = re.sub(r'<th style="', r'<th class="text-gray-800 bg-gray-50" style="', content)
content = re.sub(r'<td style="', r'<td class="text-gray-800 bg-white" style="', content)

# Change bg-white on cards to support glassmorphism
content = re.sub(r'class="bg-white p-6 rounded shadow"', r'class="bg-white/80 backdrop-blur-md p-6 rounded-xl border border-gray-200 shadow-sm"', content)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
