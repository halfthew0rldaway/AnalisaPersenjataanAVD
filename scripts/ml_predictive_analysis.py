import pandas as pd
import json
import os
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
from sklearn.preprocessing import LabelEncoder

# ==============================================================================
# MACHINE LEARNING SCRIPT: PREDICTIVE ANALYTICS FOR COMBAT PROVEN STATUS
# Algoritma: Random Forest Classifier (In-scope dengan matkul Sistem Cerdas)
# Tujuan: Menentukan faktor apa yang paling mempengaruhi alutsista menjadi "Combat Proven"
# ==============================================================================

def run_ml_analysis():
    print("🚀 Memulai proses Machine Learning (Random Forest)...")
    
    # 1. Load Dataset
    csv_path = 'weapondb.csv'
    if not os.path.exists(csv_path):
        print(f"❌ Error: File {csv_path} tidak ditemukan.")
        return

    df = pd.read_csv(csv_path)

    # 2. Preprocessing & Data Cleansing (Sama seperti logika di DashboardController)
    # Hapus row yang tidak punya tahun atau cost untuk analisis ini
    features = ['Year_Introduced', 'Unit_Cost_USD', 'Theater_of_Operation', 'Category']
    target = 'Combat_Proven'

    # Filter kolom yang dibutuhkan dan bersihkan nilai kosong
    df_clean = df[features + [target]].dropna()

    # Bersihkan string aneh di Cost & ubah jadi numerik
    df_clean['Unit_Cost_USD'] = pd.to_numeric(df_clean['Unit_Cost_USD'], errors='coerce')
    df_clean['Year_Introduced'] = pd.to_numeric(df_clean['Year_Introduced'], errors='coerce')
    df_clean = df_clean.dropna() # Hapus yang gagal di-convert

    # Encode label kategori teks menjadi numerik (Syarat Scikit-Learn)
    le_theater = LabelEncoder()
    df_clean['Theater_of_Operation'] = le_theater.fit_transform(df_clean['Theater_of_Operation'])
    
    le_category = LabelEncoder()
    df_clean['Category'] = le_category.fit_transform(df_clean['Category'])

    # Encode Target: Yes -> 1, No -> 0
    df_clean['Combat_Proven'] = df_clean['Combat_Proven'].apply(lambda x: 1 if str(x).strip().lower() == 'yes' else 0)

    # 3. Split Data (Train & Test)
    X = df_clean[features]
    y = df_clean['Combat_Proven']

    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # 4. Melatih Model Random Forest
    print("🧠 Melatih model Random Forest Classifier...")
    model = RandomForestClassifier(n_estimators=100, random_state=42, max_depth=10)
    model.fit(X_train, y_train)

    # Evaluasi Akurasi
    y_pred = model.predict(X_test)
    accuracy = accuracy_score(y_test, y_pred)
    print(f"✅ Model berhasil dilatih! Akurasi Prediksi: {accuracy * 100:.2f}%")

    # 5. Extract Feature Importance (Faktor Paling Berpengaruh)
    importances = model.feature_importances_
    
    # Mapping nama kolom agar lebih human-readable
    human_labels = {
        'Year_Introduced': 'Tahun Rilis (Usia)',
        'Unit_Cost_USD': 'Harga / Biaya',
        'Theater_of_Operation': 'Matra (Darat/Laut/Udara)',
        'Category': 'Tipe Senjata'
    }

    feature_results = []
    for i, feature in enumerate(features):
        feature_results.append({
            'factor': human_labels[feature],
            'importance_score': float(importances[i]) * 100
        })

    # Sort dari yang paling berpengaruh
    feature_results = sorted(feature_results, key=lambda x: x['importance_score'], reverse=True)

    # 6. Export hasil ke JSON agar bisa dibaca oleh Dashboard Laravel
    output_dir = 'public/data'
    os.makedirs(output_dir, exist_ok=True)
    
    output_file = os.path.join(output_dir, 'ml_insight.json')
    
    result_data = {
        'model_used': 'Random Forest Classifier',
        'accuracy': round(accuracy * 100, 2),
        'dataset_rows_used': len(df_clean),
        'insights': feature_results
    }

    with open(output_file, 'w') as f:
        json.dump(result_data, f, indent=4)
        
    print(f"📁 Hasil analisa diekspor ke: {output_file}")
    print("   Data JSON ini bisa di-fetch oleh Dashboard Laravel via AJAX.")

if __name__ == "__main__":
    run_ml_analysis()
