from flask import Flask, request, jsonify
from tensorflow.keras.models import load_model
import numpy as np
import joblib

neural_n = load_model("neural.h5", compile=False)
random_forest_reg = joblib.load('rfr_model_pipeline.pkl')
random_forest_class = joblib.load('rfc_model_pipeline.pkl')
categorical_NB_model = joblib.load('cnb_model_pipeline.pkl')

app = Flask(__name__)

@app.route('/neural', methods=['POST'])
def neural():

    data = request.get_json()
    try:
        features = np.array(data['features'], dtype=float).reshape(1, -1) 
        scaler = joblib.load('nural_scaler.pkl')
        features = scaler.transform(features)
        print(features)
        prediction = neural_n.predict(features)
        return jsonify({'prediction': prediction.tolist()})
    except Exception as e:
        print("Error during prediction:", str(e))
        return jsonify({'error': str(e)}), 500
    


@app.route('/randomForest', methods=['POST'])
def randomForest():

    data = request.get_json()
    try:
        features = np.array(data['features'], dtype=float).reshape(1, -1)

        print(features)
        prediction = random_forest_reg.predict(features)
        return jsonify({'prediction': prediction.tolist()})
    except Exception as e:
        print("Error during prediction:", str(e))
        return jsonify({'error': str(e)}), 500
    


@app.route('/randomForestClassifier', methods=['POST'])
def randomForestClassifier():

    data = request.get_json()
    try:
        features = np.array(data['features']).reshape(1, -1)
        print(features)
        prediction = random_forest_class.predict(features)
        return jsonify({'prediction': prediction.tolist()})
    except Exception as e:
        print("Error during prediction:", str(e))
        return jsonify({'error': str(e)}), 500
    
@app.route('/categorical_NB', methods=['POST'])
def categorical_NB():
    data = request.get_json()
    try:
        features = np.array(data['features']).reshape(1, -1)
        prediction = categorical_NB_model.predict(features)
        return jsonify({'prediction': prediction.tolist()})
    except Exception as e:
        print("Error during prediction:", str(e))
        return jsonify({'error': str(e)}), 500
    

if __name__ == '__main__':
    app.run(port=6060)
