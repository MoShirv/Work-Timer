<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت حضور و غیاب - الف شاپ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <style>
        body {
            font-family: Vazirmatn, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-black text-gray-900 mb-2">
                <span class="text-gray-700">الف شاپ</span>
            </h1>
            <p class="text-gray-600">مدیریت ورود و خروج</p>
        </div>

        <!-- Success Message -->
        @if(session('message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <!-- Worker Selection Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="mb-4">
                <label for="workerSelect" class="block text-gray-700 font-medium mb-2 text-right">انتخاب نیرو</label>
                <div class="relative">
                    <select id="workerSelect"
                        class="w-full px-4 py-3.5 text-base border-2 border-gray-200 rounded-xl focus:ring-3 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white shadow-sm text-right text-gray-600">
                        <option value="">نیرو را انتخاب کنید</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" class="text-right">{{ $worker->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div id="actionButtons" class="hidden mt-6 pt-6 border-t border-gray-100">
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('attendance.checkin') }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="worker_id" id="checkinWorkerId">
                        <button type="submit"
                            class="w-full font-bold bg-green-300  shadow-md text-green-800 hover:bg-green-200 py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            ثبت ورود
                        </button>
                    </form>

                    <form method="POST" action="{{ route('attendance.checkout') }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="worker_id" id="checkoutWorkerId">
                        <button type="submit"
                            class="w-full bg-rose-400 hover:bg-rose-300 shadow-md text-red-900 font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            ثبت خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">حضور امروز - {{ \Carbon\Carbon::now()->format('Y/m/d') }}
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-right font-semibold text-gray-700 border-b border-gray-200">نام
                                نیرو</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 border-b border-gray-200">ساعت
                                ورود</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 border-b border-gray-200">ساعت
                                خروج</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-700 border-b border-gray-200">وضعیت
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPresent = 0;
                            $totalAbsent = 0;
                            $totalWorking = 0;
                        @endphp

                        @foreach($workers as $worker)
                            @php
                                $att = $worker->attendances()->where('date', now()->toDateString())->first();
                                $hasCheckin = $att && $att->check_in;
                                $hasCheckout = $att && $att->check_out;

                                if ($hasCheckin && $hasCheckout) {
                                    $totalPresent++;
                                } elseif ($hasCheckin && !$hasCheckout) {
                                    $totalWorking++;
                                } else {
                                    $totalAbsent++;
                                }
                            @endphp
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4 text-right font-medium text-gray-800">{{ $worker->name }}</td>

                                <td class="py-3 px-4 text-center">
                                    @if($hasCheckin)
                                        <span
                                            class="inline-block px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                            <span class="">ثبت شد</span>
                                        </span>
                                    @else
                                        <span class="text-gray-900">____</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 text-center">
                                    @if($hasCheckout)
                                        <span
                                            class="inline-block px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">
                                            <span class="">ثبت شد</span>
                                        </span>
                                    @else
                                        <span class="text-gray-900">____</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 text-center">
                                    @if($hasCheckin && !$hasCheckout)
                                        <span
                                            class="inline-block px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                            در محل کار
                                        </span>
                                    @elseif($hasCheckin && $hasCheckout)
                                        <span
                                            class="inline-block px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
                                            تکمیل شده
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                                            ثبت‌ نشده
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.getElementById('workerSelect').addEventListener('change', function () {
                const workerId = this.value;
                const actionButtons = document.getElementById('actionButtons');

                document.getElementById('checkinWorkerId').value = workerId;
                document.getElementById('checkoutWorkerId').value = workerId;

                if (workerId) {
                    actionButtons.classList.remove('hidden');
                } else {
                    actionButtons.classList.add('hidden');
                }
            });

            // Auto-hide success message after 5 seconds
            @if(session('message'))
                setTimeout(function () {
                    const messageDiv = document.querySelector('.bg-green-50');
                    if (messageDiv) {
                        messageDiv.style.opacity = '0';
                        messageDiv.style.transition = 'opacity 0.5s';
                        setTimeout(() => messageDiv.remove(), 500);
                    }
                }, 5000);
            @endif
        </script>
</body>

</html>