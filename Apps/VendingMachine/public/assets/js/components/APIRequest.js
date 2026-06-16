export const handleApiRequest = async ({ endpoint, method, payload, onSuccess, onError }) => {
  try {
    const formData = new FormData();

    if (payload) {
        Object.entries(payload).forEach(([key, value]) => {
        formData.append(key, value);
        });
    }

    let response;

    if (method == 'POST') {
        response = await fetch(endpoint, { method: method, body: formData });
    } else {
        response = await fetch(endpoint);
    }

    if (!response.ok) throw new Error('API server unreachable');

    const data = await response.json();

    if (onSuccess) onSuccess(data);
  } catch (err) {
    if (onError) onError(err);
  }
};
