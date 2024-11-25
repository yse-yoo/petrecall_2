<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800"><a href="../">ペット検索システム</a></h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="../pet/" class="text-gray-600 hover:text-blue-500">ペット一覧</a></li>
                <?php if (empty($user['id'])): ?>
                    <li><a href="../login/" class="text-gray-600 hover:text-blue-500">ログイン</a></li>
                    <li><a href="../regist/" class="text-gray-600 hover:text-blue-500">ユーザ登録</a></li>
                <?php else: ?>
                    <li><a href="../pet/regist.php" class="text-gray-600 hover:text-blue-500">ペット登録</a></li>
                    <li><a href="../user/" class="text-gray-600 hover:text-blue-500">マイページ</a></li>
                    <li><a onclick="return confirm('ログアウトしますか？')" href="../user/logout.php" class="text-gray-600 hover:text-blue-500">ログアウト</a></li>
                <?php endif ?>
            </ul>
        </nav>
    </div>
</header>