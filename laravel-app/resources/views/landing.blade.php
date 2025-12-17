@extends('layouts.app')

@section('content')
<section id="hero" class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2 reveal">
                <span class="hero-badge">Luxury Beauty & Spa</span>
                <h1 class="hero-title mt-3 mb-3">Rayakan Pesona Dirimu dengan Treatment Terbaik</h1>
                <p class="lead text-muted mb-4">Pilih dari 31 treatment rambut & spa, atur jadwal favorit, lalu biarkan tim profesional kami memanjakanmu. Semua bisa dilakukan melalui satu dashboard personal.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('customer.login') }}" class="btn btn-rose btn-lg">Book Now</a>
                    <a href="#treatments" class="btn btn-outline-rose btn-lg">Explore Treatments</a>
                </div>
                <div class="d-flex gap-4 mt-4">
                    <div>
                        <h3 class="fw-bold mb-0">+1.2k</h3>
                        <small>Reservasi sukses per tahun</small>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">4.9/5</h3>
                        <small>Rating pelanggan puas</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=900&q=80" alt="DBeauty Skincare & Day Spa" loading="lazy">
                    <div class="floating-card">
                        <p class="mb-0 small text-muted">Today</p>
                        <p class="mb-0 fw-semibold">8 slot masih tersedia</p>
                        <small class="text-muted">Verifikasi instan oleh admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4 reveal">
                <div class="treatment-card h-100">
                    <h3 class="section-title fs-4">Therapist Profesional</h3>
                    <p>Hair & scalp specialist bersertifikat untuk setiap treatment.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="treatment-card h-100">
                    <h3 class="section-title fs-4">Smart Scheduling</h3>
                    <p>Slot real-time, reminder otomatis, dan verifikasi admin.</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="treatment-card h-100">
                    <h3 class="section-title fs-4">Signature Products</h3>
                    <p>Produk premium hair spa dengan aroma terapi menenangkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="treatments" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="hero-badge">Treatment Catalogue</span>
            <h2 class="section-title mt-3">Rangkaian Treatment Favorit</h2>
            <p class="section-subtitle mx-auto">Dari creambath tradisional hingga hair serum eksklusif, pilih paket yang sesuai kebutuhan rambutmu.</p>
            <div class="divider"></div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            @foreach (array_slice($treatments, 0, 6) as $item)
                <div class="col reveal">
                    <div class="treatment-card h-100">
                        <span class="badge-treatment">{{ $item['id'] }}</span>
                        <h4 class="mt-3 mb-2">{{ $item['name'] }}</h4>
                        <p class="text-muted mb-3">{{ $item['duration'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $item['price'] }}</strong>
                            <a href="{{ route('customer.booking') }}" class="btn btn-outline-rose btn-sm">Reserve</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="table-modern">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Treatment</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($treatments as $item)
                        <tr>
                            <td><span class="badge badge-treatment">{{ $item['id'] }}</span></td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['price'] }}</td>
                            <td>{{ $item['duration'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="steps" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="hero-badge">Cara Kerja</span>
            <h2 class="section-title mt-2">Booking Treatment Dalam 3 Langkah</h2>
            <div class="divider"></div>
        </div>
        <div class="row steps-grid">
            <div class="col-12 col-lg-4 reveal">
                <div class="card p-4 h-100">
                    <div class="card-body">
                        <h4>1. Registrasi</h4>
                        <p>Buat akun customer dengan nomor HP aktif dan password.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 reveal">
                <div class="card p-4 h-100">
                    <div class="card-body">
                        <h4>2. Pilih Jadwal</h4>
                        <p>Tentukan treatment favorit, pilih tanggal & jam yang masih tersedia.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 reveal">
                <div class="card p-4 h-100">
                    <div class="card-body">
                        <h4>3. Verifikasi & Nikmati</h4>
                        <p>Admin mengonfirmasi slotmu dan kamu siap menikmati me-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5 reveal">
                <span class="hero-badge">Testimonials</span>
                <h2 class="section-title mt-3">Cerita Dari Pelanggan Kami</h2>
                <p class="section-subtitle">Lebih dari ribuan wanita mempercayakan momen perawatan rambut & spa bersama DBeauty.</p>
                <a class="btn btn-rose mt-3" href="{{ route('customer.register') }}">Mulai Registrasi</a>
            </div>
            <div class="col-lg-7">
                <div class="testimonial-card p-4">
                    <div class="testimonial-slide active">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=150&q=80" alt="Client" class="testimonial-avatar">
                            <div>
                                <strong>Nia Pratami</strong><br>
                                <small>Entrepreneur</small>
                            </div>
                        </div>
                        <p class="fst-italic">"Booking lewat website ini super gampang. Aku bisa pilih jadwal sesuai agenda meeting dan selalu dapat reminder dari admin."</p>
                    </div>
                    <div class="testimonial-slide">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="https://i.pinimg.com/736x/bf/1f/a7/bf1fa7dc7664cdb87ff151753c8f3ca1.jpg" alt="Client" class="testimonial-avatar">
                            <div>
                                <strong>Anya Wicaksana</strong><br>
                                <small>Beauty Enthusiast</small>
                            </div>
                        </div>
                        <p class="fst-italic">"Treatment hair spa-nya bikin rileks banget. Setelah booking, aku langsung dapat konfirmasi stok therapist favoritku."</p>
                    </div>
                    <div class="testimonial-slide">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80" alt="Client" class="testimonial-avatar">
                            <div>
                                <strong>Laura Sihombing</strong><br>
                                <small>Event Planner</small>
                            </div>
                        </div>
                        <p class="fst-italic">"Dashboard-nya lengkap. Aku bisa cek riwayat reservasi dan menunggu verifikasi tanpa harus chat berkali-kali."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5 reveal">
                <div class="contact-card h-100">
                    <h2 class="section-title">Hubungi Kami</h2>
                    <p>Jl. Cempaka No.27A, Ngepos, Klaten, Kec. Klaten Tengah, Kabupaten Klaten, Jawa Tengah 57411</p>
                    <p class="mb-1">WhatsApp: 0812-8888-9999</p>
                    <p class="mb-4">Email: halo@dbeautyspa.id</p>
                    <ul class="list-unstyled">
                        <li><strong>Senin - Jumat:</strong> 09.00 - 21.00</li>
                        <li><strong>Sabtu - Minggu:</strong> 08.00 - 22.00</li>
                    </ul>
                    <div class="mt-3">
                        <a href="https://instagram.com" target="_blank" rel="noopener" class="me-3">Instagram</a>
                        <a href="https://tiktok.com" target="_blank" rel="noopener" class="me-3">TikTok</a>
                        <a href="https://wa.me/6281288889999" target="_blank" rel="noopener">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <iframe class="map-frame" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.669552840575!2d110.59116331101273!3d-7.163075371037118!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a46bbd198e36d%3A0xce9d1c9835863476!2s7JR2%2B5R9%20Klaten%2C%20Kabupaten%20Klaten%2C%20Jawa%20Tengah!5e0!3m2!1sid!2sid!4v1734363232000!5m2!1sid!2sid" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi DBeauty Spa"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
