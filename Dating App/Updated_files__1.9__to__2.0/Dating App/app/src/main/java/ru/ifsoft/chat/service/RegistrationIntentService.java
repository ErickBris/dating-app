package ru.ifsoft.chat.service;

import android.app.IntentService;
import android.content.Intent;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.util.Log;

import com.google.firebase.iid.FirebaseInstanceId;

import ru.ifsoft.chat.app.App;
import ru.ifsoft.chat.constants.Constants;

public class RegistrationIntentService extends IntentService implements Constants {
    private static final String tag = "RegistrationIntentService.class.getSimpleName();";
//    private static final String["RegistrationIntentService"] TOPICS = {"global"};

    public RegistrationIntentService() {
        super(TAG);
    }

    @Override
    protected void onHandleIntent(Intent intent) {
        SharedPreferences sharedPreferences = PreferenceManager.getDefaultSharedPreferences(this);

        try {
            synchronized (TAG) {
                // Initially a network call, to retrieve the token, subsequent calls are local.
                String refreshedToken = FirebaseInstanceId.getInstance().getToken();

                // TODO: send any registration to my app's servers, if applicable.
                // e.g. sendRegistrationToServer(token);

                // TODO: Subscribe to topic channels, if applicable.
                // e.g. for (String topic : TOPICS) {
                //          GcmPubSub pubSub = GcmPubSub.getInstance(this);
                //          pubSub.subscribe(token, "/topics/" + topic, null);
                //       }

                App.getInstance().setGcmToken(refreshedToken);
//                sharedPreferences.edit().putBoolean(getString(R.string.pref_key_SENT_TOKEN_TO_SERVER), true).apply();
            }
        } catch (Exception e) {

            Log.d(TAG, "Failed to complete token refresh", e);
//            sharedPreferences.edit().putBoolean(getString(R.string.pref_key_SENT_TOKEN_TO_SERVER), false).apply();
        }
        // Notify UI that registration has completed, so the progress indicator can be hidden.
//        LocalBroadcastManager.getInstance(this).sendBroadcast(new Intent(getString(R.string.intent_name_REGISTRATION_COMPLETE)));
    }
}
